<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class SummaryReportExport implements FromArray, WithTitle, ShouldAutoSize
{
    public function __construct(
        private array  $months,
        private        $members,
        private array  $matrix,
        private array  $monthlyTotals,
        private        $expenseBreakdown,
        private float  $totalIncome,
        private float  $totalExpense,
        private float  $balance
    ) {}

    public function title(): string
    {
        return 'Full Summary';
    }

    public function array(): array
    {
        $rows = [];

        // ── Header ──
        $rows[] = ['SUMITY – Full Summary Report (All Data)'];
        $rows[] = ['Period: ' . reset($this->months) . ' to ' . end($this->months)];
        $rows[] = ['Generated: ' . now()->format('d M Y H:i')];
        $rows[] = [];

        // ── Overall Summary ──
        $rows[] = ['OVERALL SUMMARY'];
        $rows[] = ['Total Income',  'BDT ' . number_format($this->totalIncome, 2)];
        $rows[] = ['Total Expense', 'BDT ' . number_format($this->totalExpense, 2)];
        $rows[] = ['Net Savings',   'BDT ' . number_format($this->totalIncome - $this->totalExpense, 2)];
        $rows[] = ['Fund Balance',  'BDT ' . number_format($this->balance, 2)];
        $rows[] = [];

        // ── Monthly Income/Expense Breakdown ──
        $rows[] = ['MONTHLY INCOME vs EXPENSE'];
        $rows[] = ['Month', 'Income (BDT)', 'Expense (BDT)', 'Net (BDT)'];
        foreach ($this->months as $label) {
            $inc = $this->monthlyTotals[$label]['income']  ?? 0;
            $exp = $this->monthlyTotals[$label]['expense'] ?? 0;
            $rows[] = [$label, $inc, $exp, $inc - $exp];
        }
        $rows[] = ['Total', number_format($this->totalIncome, 2), number_format($this->totalExpense, 2), number_format($this->totalIncome - $this->totalExpense, 2)];
        $rows[] = [];

        // ── Member × Month Deposit Matrix ──
        $rows[] = ['MEMBER DEPOSIT MATRIX (BDT)'];
        $header = ['Member Name', 'Code'];
        foreach ($this->months as $label) {
            $header[] = $label;
        }
        $header[] = 'Total';
        $rows[] = $header;

        foreach ($this->members as $m) {
            $row = [$m->user->name, $m->member_code];
            $memberTotal = 0;
            foreach ($this->months as $label) {
                $amt = $this->matrix[$m->id][$label] ?? 0;
                $row[] = $amt ?: 0;
                $memberTotal += $amt;
            }
            $row[] = $memberTotal;
            $rows[] = $row;
        }

        // Grand total row
        $grandRow = ['GRAND TOTAL', ''];
        foreach ($this->months as $label) {
            $grandRow[] = $this->monthlyTotals[$label]['income'] ?? 0;
        }
        $grandRow[] = $this->totalIncome;
        $rows[] = $grandRow;
        $rows[] = [];

        // ── Expense Breakdown ──
        $rows[] = ['EXPENSE BREAKDOWN BY CATEGORY'];
        $rows[] = ['Category', 'Total (BDT)'];
        foreach ($this->expenseBreakdown as $e) {
            $rows[] = [
                Expense::categories()[$e->category] ?? ucfirst($e->category),
                $e->total,
            ];
        }
        if ($this->expenseBreakdown->count()) {
            $rows[] = ['Total:', number_format($this->totalExpense, 2)];
        }

        return $rows;
    }
}
