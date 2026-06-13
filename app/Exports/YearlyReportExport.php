<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class YearlyReportExport implements FromArray, WithTitle, ShouldAutoSize
{
    public function __construct(
        private array $monthlyData,
        private $memberContributions,
        private $expenseTrends,
        private int $year,
        private float $totalIncome,
        private float $totalExpense
    ) {}

    public function title(): string
    {
        return 'Yearly Report';
    }

    public function array(): array
    {
        $rows = [];

        $rows[] = ['SUMITY – Yearly Financial Report'];
        $rows[] = ['Year: ' . $this->year];
        $rows[] = ['Generated: ' . now()->format('d M Y H:i')];
        $rows[] = [];

        $rows[] = ['SUMMARY'];
        $rows[] = ['Total Income',  'BDT ' . number_format($this->totalIncome, 2)];
        $rows[] = ['Total Expense', 'BDT ' . number_format($this->totalExpense, 2)];
        $rows[] = ['Net Savings',   'BDT ' . number_format($this->totalIncome - $this->totalExpense, 2)];
        $rows[] = [];

        $rows[] = ['MONTHLY BREAKDOWN'];
        $rows[] = ['Month', 'Income (BDT)', 'Expense (BDT)', 'Net (BDT)'];
        foreach ($this->monthlyData as $md) {
            $rows[] = [
                $md['month'],
                $md['income'],
                $md['expense'],
                $md['income'] - $md['expense'],
            ];
        }
        $rows[] = [
            'Total',
            number_format($this->totalIncome, 2),
            number_format($this->totalExpense, 2),
            number_format($this->totalIncome - $this->totalExpense, 2),
        ];
        $rows[] = [];

        $rows[] = ['MEMBER CONTRIBUTION RANKING'];
        $rows[] = ['Rank', 'Member', 'Member Code', 'Total Deposited (BDT)'];
        foreach ($this->memberContributions as $i => $m) {
            $rows[] = [$i + 1, $m->user->name, $m->member_code, $m->year_total];
        }
        $rows[] = [];

        $rows[] = ['EXPENSE BY CATEGORY'];
        $rows[] = ['Category', 'Total (BDT)'];
        foreach ($this->expenseTrends as $et) {
            $rows[] = [
                Expense::categories()[$et->category] ?? ucfirst($et->category),
                $et->total,
            ];
        }

        return $rows;
    }
}
