<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
@include('super-admin.reports.pdf._style')
</head>
<body>

<table class="header" style="width:100%;border:none;">
    <tr>
        <td style="border:none;padding:0;">
            <div class="org-name">Samite Platform</div>
            <div class="org-sub">Multi-Company SaaS Management</div>
        </td>
        <td style="border:none;padding:0;text-align:right;vertical-align:top;">
            <div class="report-title">Payment History Report</div>
            <div class="report-meta">Generated: {{ now()->format('d M Y, H:i') }}</div>
        </td>
    </tr>
</table>

<table class="summary">
    <tr style="background:#eef2ff;">
        <td class="label">Total Payments</td>
        <td class="value">{{ $payments->count() }}</td>
        <td class="label">Total Revenue</td>
        <td class="value" style="color:#4f46e5;">{{ number_format($grandTotal, 2) }}</td>
    </tr>
</table>

<table class="data">
    <thead>
        <tr>
            <th>#</th>
            <th>Company</th>
            <th>Package</th>
            <th>Period</th>
            <th class="r">Amount</th>
            <th>Reference</th>
            <th>Activated By</th>
        </tr>
    </thead>
    <tbody>
        @forelse($payments as $i => $payment)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $payment->company->name ?? '—' }}</td>
            <td>{{ $payment->package->name ?? '—' }}</td>
            <td>{{ $payment->starts_at->format('d M Y') }} – {{ $payment->ends_at->format('d M Y') }}</td>
            <td class="r">{{ number_format($payment->amount, 2) }}</td>
            <td>{{ $payment->payment_reference ?? '—' }}</td>
            <td>{{ $payment->activatedBy->name ?? '—' }}</td>
        </tr>
        @empty
        <tr><td colspan="7" class="no-data">No payments match this filter</td></tr>
        @endforelse
    </tbody>
    @if($payments->isNotEmpty())
    <tfoot>
        <tr>
            <td colspan="4" style="text-align:right;">Total:</td>
            <td class="r">{{ number_format($grandTotal, 2) }}</td>
            <td colspan="2"></td>
        </tr>
    </tfoot>
    @endif
</table>

<div class="footer">
    Samite Platform &mdash; Confidential Report &mdash; {{ now()->format('d M Y, H:i') }}
</div>
</body>
</html>
