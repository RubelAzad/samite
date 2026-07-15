<?php

namespace App\Exports\SuperAdmin;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class PaymentHistoryReportExport implements FromArray, ShouldAutoSize, WithTitle
{
    public function __construct(
        private $payments,
        private float $total,
        private string $reportTitle = 'Payment History Report'
    ) {}

    public function title(): string
    {
        return 'Payment History';
    }

    public function array(): array
    {
        $rows = [];

        $rows[] = ['Samite Platform – '.$this->reportTitle];
        $rows[] = ['Generated: '.now()->format('d M Y H:i')];
        $rows[] = [];

        $rows[] = ['#', 'Company', 'Package', 'Period', 'Amount', 'Reference', 'Activated By'];
        foreach ($this->payments as $i => $payment) {
            $rows[] = [
                $i + 1,
                $payment->company->name ?? '—',
                $payment->package->name ?? '—',
                $payment->starts_at && $payment->ends_at
                    ? $payment->starts_at->format('d M Y').' – '.$payment->ends_at->format('d M Y')
                    : '—',
                number_format($payment->amount, 2),
                $payment->payment_reference ?? '—',
                $payment->activatedBy->name ?? '—',
            ];
        }

        $rows[] = ['', '', '', 'Total:', number_format($this->total, 2), '', ''];

        return $rows;
    }
}
