<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Exports\SuperAdmin\CompanyStatusReportExport;
use App\Exports\SuperAdmin\PaymentHistoryReportExport;
use App\Exports\SuperAdmin\YearlyRevenueReportExport;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Subscription;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        $revenueByMonth = Subscription::selectRaw("DATE_FORMAT(starts_at, '%Y-%m') as month, SUM(amount) as total")
            ->groupBy('month')
            ->orderByDesc('month')
            ->limit(12)
            ->get();

        $totalRevenue = Subscription::sum('amount');

        $expiringSoon = Company::with('subscriptionPackage')
            ->whereNotNull('subscription_ends_at')
            ->whereBetween('subscription_ends_at', [now(), now()->addDays(7)])
            ->orderBy('subscription_ends_at')
            ->get();

        $expired = Company::with('subscriptionPackage')
            ->where('status', 'inactive')
            ->whereNotNull('subscription_ends_at')
            ->where('subscription_ends_at', '<', now())
            ->orderBy('subscription_ends_at')
            ->get();

        return view('super-admin.reports.index', compact('revenueByMonth', 'totalRevenue', 'expiringSoon', 'expired'));
    }

    // ─── Company-wise status report (list of companies, filterable) ───────────

    public function companies(Request $request)
    {
        $companies = $this->companiesData($request->search, $request->status);

        return view('super-admin.reports.companies', [
            'companies' => $companies,
            'search' => $request->search,
            'status' => $request->status,
        ]);
    }

    public function exportCompanies(Request $request)
    {
        $companies = $this->companiesData($request->search, $request->status);
        $filename = 'Samite-Company-Status-Report';

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('super-admin.reports.pdf.companies', compact('companies'))->setPaper('a4', 'landscape');

            return $pdf->download($filename.'.pdf');
        }

        return Excel::download(new CompanyStatusReportExport($companies), $filename.'.xlsx');
    }

    // ─── Payment / subscription history log (which company bought which package) ───

    public function payments(Request $request)
    {
        [$payments, $grandTotal] = $this->paymentsData($request->company_id, $request->from, $request->to);
        $companies = Company::orderBy('name')->get(['id', 'name']);

        return view('super-admin.reports.payments', [
            'payments' => $payments, 'grandTotal' => $grandTotal, 'companies' => $companies,
            'companyId' => $request->company_id, 'from' => $request->from, 'to' => $request->to,
        ]);
    }

    public function exportPayments(Request $request)
    {
        [$payments, $grandTotal] = $this->paymentsData($request->company_id, $request->from, $request->to);
        $filename = 'Samite-Payment-History-Report';

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('super-admin.reports.pdf.payments', compact('payments', 'grandTotal'))->setPaper('a4', 'landscape');

            return $pdf->download($filename.'.pdf');
        }

        return Excel::download(new PaymentHistoryReportExport($payments, $grandTotal), $filename.'.xlsx');
    }

    // ─── Daily / Monthly / Yearly revenue reports ──────────────────────────────

    public function daily(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        [$payments, $total] = $this->dailyData($date);

        return view('super-admin.reports.daily', compact('date', 'payments', 'total'));
    }

    public function exportDaily(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        [$payments, $total] = $this->dailyData($date);
        $filename = 'Samite-Daily-Revenue-Report-'.$date;

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('super-admin.reports.pdf.daily', compact('date', 'payments', 'total'))->setPaper('a4', 'landscape');

            return $pdf->download($filename.'.pdf');
        }

        return Excel::download(new PaymentHistoryReportExport($payments, $total, 'Daily Revenue Report — '.$date), $filename.'.xlsx');
    }

    public function monthly(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');
        [$payments, $total] = $this->monthlyData($month);

        return view('super-admin.reports.monthly', compact('month', 'payments', 'total'));
    }

    public function exportMonthly(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');
        [$payments, $total] = $this->monthlyData($month);
        $filename = 'Samite-Monthly-Revenue-Report-'.$month;

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('super-admin.reports.pdf.monthly', compact('month', 'payments', 'total'))->setPaper('a4', 'landscape');

            return $pdf->download($filename.'.pdf');
        }

        return Excel::download(new PaymentHistoryReportExport($payments, $total, 'Monthly Revenue Report — '.$month), $filename.'.xlsx');
    }

    public function yearly(Request $request)
    {
        $year = (int) ($request->year ?? now()->year);
        [$monthlyTotals, $total] = $this->yearlyData($year);

        return view('super-admin.reports.yearly', compact('year', 'monthlyTotals', 'total'));
    }

    public function exportYearly(Request $request)
    {
        $year = (int) ($request->year ?? now()->year);
        [$monthlyTotals, $total] = $this->yearlyData($year);
        $filename = 'Samite-Yearly-Revenue-Report-'.$year;

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('super-admin.reports.pdf.yearly', compact('year', 'monthlyTotals', 'total'))->setPaper('a4', 'portrait');

            return $pdf->download($filename.'.pdf');
        }

        return Excel::download(new YearlyRevenueReportExport($monthlyTotals, $total, $year), $filename.'.xlsx');
    }

    // ─── Data helpers ───────────────────────────────────────────────────────────

    private function companiesData(?string $search, ?string $status)
    {
        return Company::with('subscriptionPackage')
            ->withSum('subscriptions', 'amount')
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->get()
            ->when($status, fn ($items) => $items->filter(fn ($c) => $c->subscriptionStatus() === $status))
            ->values();
    }

    private function paymentsData(?string $companyId, ?string $from, ?string $to): array
    {
        $payments = Subscription::with(['company', 'package', 'activatedBy'])
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->when($from, fn ($q) => $q->whereDate('starts_at', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('starts_at', '<=', $to))
            ->orderByDesc('starts_at')
            ->get();

        return [$payments, $payments->sum('amount')];
    }

    private function dailyData(string $date): array
    {
        $payments = Subscription::with(['company', 'package'])
            ->whereDate('starts_at', $date)
            ->orderBy('company_id')
            ->get();

        return [$payments, $payments->sum('amount')];
    }

    private function monthlyData(string $month): array
    {
        [$year, $mon] = explode('-', $month);

        $payments = Subscription::with(['company', 'package'])
            ->whereYear('starts_at', $year)
            ->whereMonth('starts_at', $mon)
            ->orderBy('starts_at')
            ->get();

        return [$payments, $payments->sum('amount')];
    }

    private function yearlyData(int $year): array
    {
        $monthlyTotals = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyTotals[] = [
                'month' => date('F', mktime(0, 0, 0, $m, 1)),
                'total' => Subscription::whereYear('starts_at', $year)->whereMonth('starts_at', $m)->sum('amount'),
            ];
        }

        return [$monthlyTotals, collect($monthlyTotals)->sum('total')];
    }
}
