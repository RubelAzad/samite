<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class DailyReportExport implements FromArray, WithTitle, ShouldAutoSize
{
    public function __construct(
        private $deposits,
        private $expenses,
        private string $date,
        private float $totalIncome,
        private float $totalExpense,
        private float $net
    ) {}

    public function title(): string
    {
        return 'Daily Report';
    }

    public function array(): array
    {
        $rows = [];

        $rows[] = ['SUMITY – Daily Financial Report'];
        $rows[] = ['Date: ' . \Carbon\Carbon::parse($this->date)->format('d M Y')];
        $rows[] = ['Generated: ' . now()->format('d M Y H:i')];
        $rows[] = [];

        $rows[] = ['SUMMARY'];
        $rows[] = ['Total Income',  'BDT ' . number_format($this->totalIncome, 2)];
        $rows[] = ['Total Expense', 'BDT ' . number_format($this->totalExpense, 2)];
        $rows[] = ['Net',           'BDT ' . number_format($this->net, 2)];
        $rows[] = [];

        $rows[] = ['DEPOSITS (' . $this->deposits->count() . ')'];
        $rows[] = ['#', 'Member', 'Member Code', 'Amount (BDT)', 'Payment Method'];
        foreach ($this->deposits as $i => $d) {
            $rows[] = [
                $i + 1,
                $d->member->user->name ?? 'N/A',
                $d->member->member_code ?? '',
                $d->amount,
                ucfirst($d->payment_method),
            ];
        }
        $rows[] = ['', '', 'Total:', number_format($this->totalIncome, 2), ''];
        $rows[] = [];

        $rows[] = ['EXPENSES (' . $this->expenses->count() . ')'];
        $rows[] = ['#', 'Title', 'Category', 'Amount (BDT)', 'Notes'];
        foreach ($this->expenses as $i => $e) {
            $rows[] = [
                $i + 1,
                $e->title,
                ucfirst($e->category),
                $e->amount,
                $e->notes ?? '',
            ];
        }
        $rows[] = ['', '', 'Total:', number_format($this->totalExpense, 2), ''];

        return $rows;
    }
}
