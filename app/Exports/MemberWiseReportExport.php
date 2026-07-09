<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class MemberWiseReportExport implements FromArray, WithTitle, ShouldAutoSize
{
    public function __construct(
        private $members,
        private float $grandTotal,
        private ?string $from,
        private ?string $to,
        private $selectedMember = null
    ) {}

    public function title(): string
    {
        return 'Member-Wise Report';
    }

    public function array(): array
    {
        $rows = [];

        $rows[] = ['Attonirbhor Joutho Uddyog – Member-Wise Payment Report'];
        if ($this->selectedMember) {
            $rows[] = ['Member: ' . $this->selectedMember->user->name . ' (' . $this->selectedMember->member_code . ')'];
        }
        if ($this->from && $this->to) {
            $rows[] = ['Period: ' . $this->from . ' to ' . $this->to];
        } else {
            $rows[] = ['Period: All Time'];
        }
        $rows[] = ['Generated: ' . now()->format('d M Y H:i')];
        $rows[] = [];

        $rows[] = ['#', 'Date', 'Amount (BDT)', 'Payment Method', 'Notes'];

        foreach ($this->members as $group) {
            foreach ($group->deposits as $i => $d) {
                $rows[] = [
                    $i + 1,
                    $d->date->format('d M Y'),
                    $d->amount,
                    ucfirst($d->payment_method ?? '—'),
                    $d->notes ?? '—',
                ];
            }
            $rows[] = ['', 'Subtotal:', number_format($group->total, 2), '', ''];
            $rows[] = [];
        }

        if ($this->grandTotal > 0) {
            $rows[] = ['', 'GRAND TOTAL:', number_format($this->grandTotal, 2), '', ''];
        }

        return $rows;
    }
}
