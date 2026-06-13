<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class MonthlyReportExport implements FromArray, WithTitle, ShouldAutoSize
{
    public function __construct(
        private $memberDeposits,
        private $expenseBreakdown,
        private string $month,
        private float $totalIncome,
        private float $totalExpense,
        private float $net,
        private float $balance
    ) {}

    public function title(): string
    {
        return 'Monthly Report';
    }

    public function array(): array
    {
        [$year, $mon] = explode('-', $this->month);
        $label = \Carbon\Carbon::create($year, $mon)->format('F Y');

        $rows = [];

        $rows[] = ['SUMITY – Monthly Financial Report'];
        $rows[] = ['Period: ' . $label];
        $rows[] = ['Generated: ' . now()->format('d M Y H:i')];
        $rows[] = [];

        $rows[] = ['SUMMARY'];
        $rows[] = ['Total Income',  'BDT ' . number_format($this->totalIncome, 2)];
        $rows[] = ['Total Expense', 'BDT ' . number_format($this->totalExpense, 2)];
        $rows[] = ['Net',           'BDT ' . number_format($this->net, 2)];
        $rows[] = ['Fund Balance',  'BDT ' . number_format($this->balance, 2)];
        $rows[] = [];

        $rows[] = ['MEMBER CONTRIBUTIONS'];
        $rows[] = ['#', 'Member', 'Member Code', 'Amount (BDT)'];
        $rank = 1;
        foreach ($this->memberDeposits as $m) {
            if ($m->month_total > 0) {
                $rows[] = [$rank++, $m->user->name, $m->member_code, $m->month_total];
            }
        }
        $rows[] = ['', '', 'Total:', number_format($this->totalIncome, 2)];
        $rows[] = [];

        $rows[] = ['EXPENSE BREAKDOWN BY CATEGORY'];
        $rows[] = ['Category', 'Amount (BDT)'];
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
