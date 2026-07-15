<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Barryvdh\DomPDF\Facade\Pdf;

class VoucherController extends Controller
{
    public function show(Subscription $subscription)
    {
        $subscription->load(['company', 'package', 'activatedBy']);

        $pdf = Pdf::loadView('super-admin.vouchers.pdf', compact('subscription'))->setPaper('a5', 'portrait');

        return $pdf->download("Voucher-{$subscription->id}.pdf");
    }
}
