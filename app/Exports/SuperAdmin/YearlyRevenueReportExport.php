<?php

namespace App\Exports\SuperAdmin;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class YearlyRevenueReportExport implements FromArray, ShouldAutoSize, WithTitle
{
    public function __construct(
        private array $monthlyTotals,
        private float $total,
        private int $year
    ) {}

    public function title(): string
    {
        return 'Yearly Revenue';
    }

    public function array(): array
    {
        $rows = [];

        $rows[] = ['Samite Platform – Yearly Revenue Report '.$this->year];
        $rows[] = ['Generated: '.now()->format('d M Y H:i')];
        $rows[] = [];

        $rows[] = ['Month', 'Revenue'];
        foreach ($this->monthlyTotals as $row) {
            $rows[] = [$row['month'].' '.$this->year, number_format($row['total'], 2)];
        }
        $rows[] = ['Total:', number_format($this->total, 2)];

        return $rows;
    }
}
