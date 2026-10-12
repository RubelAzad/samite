<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DailyReportExport;
use App\Exports\MemberWiseReportExport;
use App\Exports\MonthlyReportExport;
use App\Exports\SummaryReportExport;
use App\Exports\YearlyReportExport;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Expense;
use App\Models\LedgerEntry;
use App\Models\Member;
use App\Models\Withdrawal;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    // ─── View methods ──────────────────────────────────────────────────────────

    public function daily(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        [$deposits, $withdrawals, $expenses, $totalIncome, $totalWithdrawals, $totalExpense, $net] = $this->dailyData($date);

        return view('admin.reports.daily', compact('date', 'deposits', 'withdrawals', 'expenses', 'totalIncome', 'totalWithdrawals', 'totalExpense', 'net'));
    }

    public function monthly(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');
        [$memberDeposits, $expenseBreakdown, $totalIncome, $totalWithdrawals, $totalExpense, $net, $balance] = $this->monthlyData($month);

        return view('admin.reports.monthly', compact(
            'month', 'memberDeposits', 'expenseBreakdown',
            'totalIncome', 'totalWithdrawals', 'totalExpense', 'net', 'balance'
        ));
    }

    public function yearly(Request $request)
    {
        $year = (int) ($request->year ?? now()->year);
        [$monthlyData, $memberContributions, $expenseTrends, $totalIncome, $totalWithdrawals, $totalExpense] = $this->yearlyData($year);

        return view('admin.reports.yearly', compact(
            'year', 'monthlyData', 'memberContributions',
            'expenseTrends', 'totalIncome', 'totalWithdrawals', 'totalExpense'
        ));
    }

    // ─── Print methods (browser PDF with Bengali font support) ──────────────────

    public function printDaily(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        [$deposits, $withdrawals, $expenses, $totalIncome, $totalWithdrawals, $totalExpense, $net] = $this->dailyData($date);

        return view('admin.reports.print.daily', compact('date', 'deposits', 'withdrawals', 'expenses', 'totalIncome', 'totalWithdrawals', 'totalExpense', 'net'));
    }

    public function printMonthly(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');
        [$memberDeposits, $expenseBreakdown, $totalIncome, $totalWithdrawals, $totalExpense, $net, $balance] = $this->monthlyData($month);

        return view('admin.reports.print.monthly', compact('month', 'memberDeposits', 'expenseBreakdown', 'totalIncome', 'totalWithdrawals', 'totalExpense', 'net', 'balance'));
    }

    public function printYearly(Request $request)
    {
        $year = (int) ($request->year ?? now()->year);
        [$monthlyData, $memberContributions, $expenseTrends, $totalIncome, $totalWithdrawals, $totalExpense] = $this->yearlyData($year);

        return view('admin.reports.print.yearly', compact('year', 'monthlyData', 'memberContributions', 'expenseTrends', 'totalIncome', 'totalWithdrawals', 'totalExpense'));
    }

    // ─── Member‑wise Payment Report ──────────────────────────────────────────────

    public function memberWise(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $memberId = $request->member_id;
        $selectedMember = $memberId ? Member::with('user')->find($memberId) : null;

        [$members, $grandTotal] = $this->memberWiseData($from, $to, $memberId ? (int) $memberId : null);

        return view('admin.reports.member-wise', compact('from', 'to', 'members', 'grandTotal', 'selectedMember'));
    }

    public function printMemberWise(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $memberId = $request->member_id;
        $selectedMember = $memberId ? Member::with('user')->find($memberId) : null;

        [$members, $grandTotal] = $this->memberWiseData($from, $to, $memberId ? (int) $memberId : null);

        return view('admin.reports.print.member-wise', compact('from', 'to', 'members', 'grandTotal', 'selectedMember'));
    }

    public function exportMemberWise(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $format = $request->format ?? 'excel';
        $memberId = $request->member_id;

        [$members, $grandTotal] = $this->memberWiseData($from, $to, $memberId ? (int) $memberId : null);

        $selectedMember = $memberId ? Member::with('user')->find($memberId) : null;

        $filename = 'ATTONIRVOR-MemberWise-Report';
        if ($selectedMember) {
            $filename .= '-'.str_replace(' ', '_', $selectedMember->user->name);
        }
        if ($from && $to) {
            $filename .= '-'.$from.'_to_'.$to;
        }

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf.member-wise', compact(
                'from', 'to', 'members', 'grandTotal', 'selectedMember'
            ))->setPaper('a4', 'landscape');

            return $pdf->download($filename.'.pdf');
        }

        return Excel::download(
            new MemberWiseReportExport($members, $grandTotal, $from, $to, $selectedMember),
            $filename.'.xlsx'
        );
    }

    // ─── Export methods ─────────────────────────────────────────────────────────

    public function exportDaily(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        $format = $request->format ?? 'excel';
        [$deposits, $withdrawals, $expenses, $totalIncome, $totalWithdrawals, $totalExpense, $net] = $this->dailyData($date);

        $filename = 'ATTONIRVOR-Daily-Report-'.$date;

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf.daily', compact(
                'date', 'deposits', 'withdrawals', 'expenses', 'totalIncome', 'totalWithdrawals', 'totalExpense', 'net'
            ))->setPaper('a4', 'landscape');

            return $pdf->download($filename.'.pdf');
        }

        return Excel::download(
            new DailyReportExport($deposits, $withdrawals, $expenses, $date, $totalIncome, $totalWithdrawals, $totalExpense, $net),
            $filename.'.xlsx'
        );
    }

    public function exportMonthly(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');
        $format = $request->format ?? 'excel';
        [$memberDeposits, $expenseBreakdown, $totalIncome, $totalWithdrawals, $totalExpense, $net, $balance] = $this->monthlyData($month);

        $filename = 'ATTONIRVOR-Monthly-Report-'.$month;

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf.monthly', compact(
                'month', 'memberDeposits', 'expenseBreakdown',
                'totalIncome', 'totalWithdrawals', 'totalExpense', 'net', 'balance'
            ))->setPaper('a4', 'portrait');

            return $pdf->download($filename.'.pdf');
        }

        return Excel::download(
            new MonthlyReportExport($memberDeposits, $expenseBreakdown, $month, $totalIncome, $totalWithdrawals, $totalExpense, $net, $balance),
            $filename.'.xlsx'
        );
    }

    public function exportYearly(Request $request)
    {
        $year = (int) ($request->year ?? now()->year);
        $format = $request->format ?? 'excel';
        [$monthlyData, $memberContributions, $expenseTrends, $totalIncome, $totalWithdrawals, $totalExpense] = $this->yearlyData($year);

        $filename = 'ATTONIRVOR-Yearly-Report-'.$year;

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf.yearly', compact(
                'year', 'monthlyData', 'memberContributions',
                'expenseTrends', 'totalIncome', 'totalWithdrawals', 'totalExpense'
            ))->setPaper('a4', 'portrait');

            return $pdf->download($filename.'.pdf');
        }

        return Excel::download(
            new YearlyReportExport($monthlyData, $memberContributions, $expenseTrends, $year, $totalIncome, $totalWithdrawals, $totalExpense),
            $filename.'.xlsx'
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
            'ATTONIRVOR-Full-Summary-Report.xlsx'
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
        $startDate = $first ? Carbon::parse($first->date)->startOfMonth() : now()->startOfMonth();
        $endDate = now()->startOfMonth();

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
            $label = Carbon::create($row->yr, $row->mo)->format('M Y');
            $matrix[$row->member_id][$label] = (float) $row->total;
        }

        // Withdrawal matrix: member_id → [month_label → amount]
        $rawWithdrawals = Withdrawal::where('status', 'approved')
            ->selectRaw('member_id, YEAR(date) as yr, MONTH(date) as mo, SUM(amount) as total')
            ->groupBy('member_id', 'yr', 'mo')
            ->get();

        $withdrawalMatrix = [];
        foreach ($rawWithdrawals as $row) {
            $label = Carbon::create($row->yr, $row->mo)->format('M Y');
            $withdrawalMatrix[$row->member_id][$label] = (float) $row->total;
        }

        // Monthly income/expense/withdrawal totals
        $monthlyTotals = [];
        $cursor = $startDate->copy();
        while ($cursor <= $endDate) {
            $label = $cursor->format('M Y');
            $monthlyTotals[$label] = [
                'income' => Deposit::where('status', 'approved')
                    ->whereYear('date', $cursor->year)->whereMonth('date', $cursor->month)
                    ->sum('amount'),
                'withdrawals' => Withdrawal::where('status', 'approved')
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

        $totalIncome = Deposit::where('status', 'approved')->sum('amount');
        $totalWithdrawals = Withdrawal::where('status', 'approved')->sum('amount');
        $totalExpense = Expense::where('status', 'approved')->sum('amount');
        $balance = LedgerEntry::currentBalance();

        return compact('months', 'members', 'matrix', 'withdrawalMatrix', 'monthlyTotals',
            'expenseBreakdown', 'totalIncome', 'totalWithdrawals', 'totalExpense', 'balance',
            'startDate', 'endDate');
    }

    private function dailyData(string $date): array
    {
        $deposits = Deposit::with('member.user')
            ->where('status', 'approved')
            ->whereDate('date', $date)
            ->get();

        $withdrawals = Withdrawal::with('member.user')
            ->where('status', 'approved')
            ->whereDate('date', $date)
            ->get();

        $expenses = Expense::with('createdBy')
            ->where('status', 'approved')
            ->whereDate('date', $date)
            ->get();

        $totalIncome = $deposits->sum('amount');
        $totalWithdrawals = $withdrawals->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $net = $totalIncome - $totalWithdrawals - $totalExpense;

        return [$deposits, $withdrawals, $expenses, $totalIncome, $totalWithdrawals, $totalExpense, $net];
    }

    private function monthlyData(string $month): array
    {
        [$year, $mon] = explode('-', $month);

        $memberDeposits = Member::with(['user', 'deposits' => function ($q) use ($year, $mon) {
            $q->where('status', 'approved')
                ->whereYear('date', $year)
                ->whereMonth('date', $mon);
        }, 'withdrawals' => function ($q) use ($year, $mon) {
            $q->where('status', 'approved')
                ->whereYear('date', $year)
                ->whereMonth('date', $mon);
        }])->get()->map(function ($m) {
            $m->month_deposits = $m->deposits->sum('amount');
            $m->month_withdrawals = $m->withdrawals->sum('amount');
            $m->month_total = $m->month_deposits - $m->month_withdrawals;

            return $m;
        })->sortByDesc('month_total');

        $expenseBreakdown = Expense::where('status', 'approved')
            ->whereYear('date', $year)
            ->whereMonth('date', $mon)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        $totalIncome = $memberDeposits->sum('month_deposits');
        $totalWithdrawals = $memberDeposits->sum('month_withdrawals');
        $totalExpense = $expenseBreakdown->sum('total');
        $net = $totalIncome - $totalWithdrawals - $totalExpense;
        $balance = LedgerEntry::currentBalance();

        return [$memberDeposits, $expenseBreakdown, $totalIncome, $totalWithdrawals, $totalExpense, $net, $balance];
    }

    private function yearlyData(int $year): array
    {
        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[] = [
                'month' => date('F', mktime(0, 0, 0, $m, 1)),
                'income' => Deposit::where('status', 'approved')->whereYear('date', $year)->whereMonth('date', $m)->sum('amount'),
                'withdrawals' => Withdrawal::where('status', 'approved')->whereYear('date', $year)->whereMonth('date', $m)->sum('amount'),
                'expense' => Expense::where('status', 'approved')->whereYear('date', $year)->whereMonth('date', $m)->sum('amount'),
            ];
        }

        $memberContributions = Member::with('user')
            ->get()
            ->map(function ($m) use ($year) {
                $m->year_deposits = $m->deposits()->where('status', 'approved')->whereYear('date', $year)->sum('amount');
                $m->year_withdrawals = $m->withdrawals()->where('status', 'approved')->whereYear('date', $year)->sum('amount');
                $m->year_total = $m->year_deposits - $m->year_withdrawals;

                return $m;
            })
            ->sortByDesc('year_total');

        $expenseTrends = Expense::where('status', 'approved')
            ->whereYear('date', $year)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        $totalIncome = collect($monthlyData)->sum('income');
        $totalWithdrawals = collect($monthlyData)->sum('withdrawals');
        $totalExpense = collect($monthlyData)->sum('expense');

        return [$monthlyData, $memberContributions, $expenseTrends, $totalIncome, $totalWithdrawals, $totalExpense];
    }

    private function memberWiseData(?string $from, ?string $to, ?int $memberId = null): array
    {
        $deposits = Deposit::with('member.user')
            ->where('status', 'approved')
            ->when($from, fn ($q) => $q->whereDate('date', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('date', '<=', $to))
            ->when($memberId, fn ($q) => $q->where('member_id', $memberId))
            ->orderBy('date')
            ->get();

        $withdrawals = Withdrawal::with('member.user')
            ->where('status', 'approved')
            ->when($from, fn ($q) => $q->whereDate('date', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('date', '<=', $to))
            ->when($memberId, fn ($q) => $q->where('member_id', $memberId))
            ->orderBy('date')
            ->get();

        $members = $deposits->groupBy('member_id')->map(function ($items) use ($withdrawals) {
            $member = $items->first()->member;
            $memberWithdrawals = $withdrawals->where('member_id', $member->id);

            return (object) [
                'member' => $member,
                'deposits' => $items,
                'withdrawals' => $memberWithdrawals,
                'total' => $items->sum('amount'),
                'withdrawal_total' => $memberWithdrawals->sum('amount'),
            ];
        })->sortByDesc('total')->values();

        $grandTotal = $deposits->sum('amount');

        return [$members, $grandTotal];
    }
}
