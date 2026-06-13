<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DailyReportExport;
use App\Exports\MonthlyReportExport;
use App\Exports\SummaryReportExport;
use App\Exports\YearlyReportExport;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Expense;
use App\Models\LedgerEntry;
use App\Models\Member;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    // ─── View methods ──────────────────────────────────────────────────────────

    public function daily(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        [$deposits, $expenses, $totalIncome, $totalExpense, $net] = $this->dailyData($date);

        return view('admin.reports.daily', compact('date', 'deposits', 'expenses', 'totalIncome', 'totalExpense', 'net'));
    }

    public function monthly(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');
        [$memberDeposits, $expenseBreakdown, $totalIncome, $totalExpense, $net, $balance] = $this->monthlyData($month);

        return view('admin.reports.monthly', compact(
            'month', 'memberDeposits', 'expenseBreakdown',
            'totalIncome', 'totalExpense', 'net', 'balance'
        ));
    }

    public function yearly(Request $request)
    {
        $year = (int) ($request->year ?? now()->year);
        [$monthlyData, $memberContributions, $expenseTrends, $totalIncome, $totalExpense] = $this->yearlyData($year);

        return view('admin.reports.yearly', compact(
            'year', 'monthlyData', 'memberContributions',
            'expenseTrends', 'totalIncome', 'totalExpense'
        ));
    }

    // ─── Print methods (browser PDF with Bengali font support) ──────────────────

    public function printDaily(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        [$deposits, $expenses, $totalIncome, $totalExpense, $net] = $this->dailyData($date);
        return view('admin.reports.print.daily', compact('date', 'deposits', 'expenses', 'totalIncome', 'totalExpense', 'net'));
    }

    public function printMonthly(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');
        [$memberDeposits, $expenseBreakdown, $totalIncome, $totalExpense, $net, $balance] = $this->monthlyData($month);
        return view('admin.reports.print.monthly', compact('month', 'memberDeposits', 'expenseBreakdown', 'totalIncome', 'totalExpense', 'net', 'balance'));
    }

    public function printYearly(Request $request)
    {
        $year = (int) ($request->year ?? now()->year);
        [$monthlyData, $memberContributions, $expenseTrends, $totalIncome, $totalExpense] = $this->yearlyData($year);
        return view('admin.reports.print.yearly', compact('year', 'monthlyData', 'memberContributions', 'expenseTrends', 'totalIncome', 'totalExpense'));
    }

    // ─── Export methods ─────────────────────────────────────────────────────────

    public function exportDaily(Request $request)
    {
        $date   = $request->date ?? now()->toDateString();
        $format = $request->format ?? 'excel';
        [$deposits, $expenses, $totalIncome, $totalExpense, $net] = $this->dailyData($date);

        $filename = 'SUMITY-Daily-Report-' . $date;

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf.daily', compact(
                'date', 'deposits', 'expenses', 'totalIncome', 'totalExpense', 'net'
            ))->setPaper('a4', 'landscape');
            return $pdf->download($filename . '.pdf');
        }

        return Excel::download(
            new DailyReportExport($deposits, $expenses, $date, $totalIncome, $totalExpense, $net),
            $filename . '.xlsx'
        );
    }

    public function exportMonthly(Request $request)
    {
        $month  = $request->month ?? now()->format('Y-m');
        $format = $request->format ?? 'excel';
        [$memberDeposits, $expenseBreakdown, $totalIncome, $totalExpense, $net, $balance] = $this->monthlyData($month);

        $filename = 'SUMITY-Monthly-Report-' . $month;

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf.monthly', compact(
                'month', 'memberDeposits', 'expenseBreakdown',
                'totalIncome', 'totalExpense', 'net', 'balance'
            ))->setPaper('a4', 'portrait');
            return $pdf->download($filename . '.pdf');
        }

        return Excel::download(
            new MonthlyReportExport($memberDeposits, $expenseBreakdown, $month, $totalIncome, $totalExpense, $net, $balance),
            $filename . '.xlsx'
        );
    }

    public function exportYearly(Request $request)
    {
        $year   = (int) ($request->year ?? now()->year);
        $format = $request->format ?? 'excel';
        [$monthlyData, $memberContributions, $expenseTrends, $totalIncome, $totalExpense] = $this->yearlyData($year);

        $filename = 'SUMITY-Yearly-Report-' . $year;

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf.yearly', compact(
                'year', 'monthlyData', 'memberContributions',
                'expenseTrends', 'totalIncome', 'totalExpense'
            ))->setPaper('a4', 'portrait');
            return $pdf->download($filename . '.pdf');
        }

        return Excel::download(
            new YearlyReportExport($monthlyData, $memberContributions, $expenseTrends, $year, $totalIncome, $totalExpense),
            $filename . '.xlsx'
        );
    }

    // ─── Full Summary (all-time, cross-year) ────────────────────────────────────

    public function summary()
    {
        $data = $this->summaryData();
        return view('admin.reports.summary', $data);
    }

    public function exportSummary()
    {
        $data = $this->summaryData();
        return Excel::download(
            new SummaryReportExport(
                $data['months'], $data['members'], $data['matrix'],
                $data['monthlyTotals'], $data['expenseBreakdown'],
                $data['totalIncome'], $data['totalExpense'], $data['balance']
            ),
            'SUMITY-Full-Summary-Report.xlsx'
        );
    }

    public function printSummary()
    {
        $data = $this->summaryData();
        return view('admin.reports.print.summary', $data);
    }

    // ─── Data helpers ───────────────────────────────────────────────────────────

    private function summaryData(): array
    {
        $first = Deposit::where('status', 'approved')->orderBy('date')->first();
        $startDate = $first ? \Carbon\Carbon::parse($first->date)->startOfMonth() : now()->startOfMonth();
        $endDate   = now()->startOfMonth();

        // Build list of month labels
        $months = [];
        $cursor = $startDate->copy();
        while ($cursor <= $endDate) {
            $months[] = $cursor->format('M Y');
            $cursor->addMonth();
        }

        // Deposit matrix: member_id → [month_label → amount]
        $rawDeposits = Deposit::where('status', 'approved')
            ->selectRaw('member_id, YEAR(date) as yr, MONTH(date) as mo, SUM(amount) as total')
            ->groupBy('member_id', 'yr', 'mo')
            ->get();

        $matrix = [];
        foreach ($rawDeposits as $row) {
            $label = \Carbon\Carbon::create($row->yr, $row->mo)->format('M Y');
            $matrix[$row->member_id][$label] = (float) $row->total;
        }

        // Monthly income/expense totals
        $monthlyTotals = [];
        $cursor = $startDate->copy();
        while ($cursor <= $endDate) {
            $label = $cursor->format('M Y');
            $monthlyTotals[$label] = [
                'income'  => Deposit::where('status', 'approved')
                    ->whereYear('date', $cursor->year)->whereMonth('date', $cursor->month)
                    ->sum('amount'),
                'expense' => Expense::where('status', 'approved')
                    ->whereYear('date', $cursor->year)->whereMonth('date', $cursor->month)
                    ->sum('amount'),
            ];
            $cursor->addMonth();
        }

        $members = Member::with('user')->orderBy('member_code')->get();

        $expenseBreakdown = Expense::where('status', 'approved')
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $totalIncome  = Deposit::where('status', 'approved')->sum('amount');
        $totalExpense = Expense::where('status', 'approved')->sum('amount');
        $balance      = LedgerEntry::currentBalance();

        return compact('months', 'members', 'matrix', 'monthlyTotals',
                       'expenseBreakdown', 'totalIncome', 'totalExpense', 'balance',
                       'startDate', 'endDate');
    }

    private function dailyData(string $date): array
    {
        $deposits = Deposit::with('member.user')
            ->where('status', 'approved')
            ->whereDate('date', $date)
            ->get();

        $expenses = Expense::with('createdBy')
            ->where('status', 'approved')
            ->whereDate('date', $date)
            ->get();

        $totalIncome  = $deposits->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $net          = $totalIncome - $totalExpense;

        return [$deposits, $expenses, $totalIncome, $totalExpense, $net];
    }

    private function monthlyData(string $month): array
    {
        [$year, $mon] = explode('-', $month);

        $memberDeposits = Member::with(['user', 'deposits' => function ($q) use ($year, $mon) {
            $q->where('status', 'approved')
              ->whereYear('date', $year)
              ->whereMonth('date', $mon);
        }])->get()->map(function ($m) {
            $m->month_total = $m->deposits->sum('amount');
            return $m;
        })->sortByDesc('month_total');

        $expenseBreakdown = Expense::where('status', 'approved')
            ->whereYear('date', $year)
            ->whereMonth('date', $mon)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        $totalIncome  = $memberDeposits->sum('month_total');
        $totalExpense = $expenseBreakdown->sum('total');
        $net          = $totalIncome - $totalExpense;
        $balance      = LedgerEntry::currentBalance();

        return [$memberDeposits, $expenseBreakdown, $totalIncome, $totalExpense, $net, $balance];
    }

    private function yearlyData(int $year): array
    {
        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[] = [
                'month'   => date('F', mktime(0, 0, 0, $m, 1)),
                'income'  => Deposit::where('status', 'approved')->whereYear('date', $year)->whereMonth('date', $m)->sum('amount'),
                'expense' => Expense::where('status', 'approved')->whereYear('date', $year)->whereMonth('date', $m)->sum('amount'),
            ];
        }

        $memberContributions = Member::with('user')
            ->get()
            ->map(function ($m) use ($year) {
                $m->year_total = $m->deposits()->where('status', 'approved')->whereYear('date', $year)->sum('amount');
                return $m;
            })
            ->sortByDesc('year_total');

        $expenseTrends = Expense::where('status', 'approved')
            ->whereYear('date', $year)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        $totalIncome  = collect($monthlyData)->sum('income');
        $totalExpense = collect($monthlyData)->sum('expense');

        return [$monthlyData, $memberContributions, $expenseTrends, $totalIncome, $totalExpense];
    }
}
