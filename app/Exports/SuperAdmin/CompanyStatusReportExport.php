<?php

namespace App\Exports\SuperAdmin;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class CompanyStatusReportExport implements FromArray, ShouldAutoSize, WithTitle
{
    public function __construct(private $companies) {}

    public function title(): string
    {
        return 'Company Status Report';
    }

    public function array(): array
    {
        $rows = [];

        $rows[] = ['Samite Platform – Company Status Report'];
        $rows[] = ['Generated: '.now()->format('d M Y H:i')];
        $rows[] = [];

        $rows[] = ['#', 'Company', 'Package', 'Expires', 'Total Revenue', 'Status'];
        foreach ($this->companies as $i => $company) {
            $rows[] = [
                $i + 1,
                $company->name,
                $company->subscriptionPackage->name ?? '—',
                $company->subscription_ends_at?->format('d M Y') ?? '—',
                number_format($company->subscriptions_sum_amount ?? 0, 2),
                ucfirst($company->subscriptionStatus()),
            ];
        }

        return $rows;
    }
}
