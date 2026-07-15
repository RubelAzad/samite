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
            <div class="report-title">Monthly Revenue Report</div>
            <div class="report-meta">Month: {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}</div>
            <div class="report-meta">Generated: {{ now()->format('d M Y, H:i') }}</div>
        </td>
    </tr>
</table>

<table class="data">
    <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Company</th>
            <th>Package</th>
            <th class="r">Amount</th>
        </tr>
    </thead>
    <tbody>
        @forelse($payments as $i => $payment)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $payment->starts_at->format('d M Y') }}</td>
            <td>{{ $payment->company->name ?? '—' }}</td>
            <td>{{ $payment->package->name ?? '—' }}</td>
            <td class="r">{{ number_format($payment->amount, 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="5" class="no-data">No payments recorded for this month</td></tr>
        @endforelse
    </tbody>
    @if($payments->isNotEmpty())
    <tfoot>
        <tr>
            <td colspan="4" style="text-align:right;">Total:</td>
            <td class="r">{{ number_format($total, 2) }}</td>
        </tr>
    </tfoot>
    @endif
</table>

<div class="footer">
    Samite Platform &mdash; Confidential Report &mdash; {{ now()->format('d M Y, H:i') }}
</div>
</body>
</html>
