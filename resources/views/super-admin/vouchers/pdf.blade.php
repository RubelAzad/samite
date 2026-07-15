<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #1f2937; background: #fff; padding: 28px; }
.voucher { border: 2px solid #4f46e5; border-radius: 6px; padding: 24px; }
.header { text-align: center; border-bottom: 2px dashed #c7d2fe; padding-bottom: 16px; margin-bottom: 18px; }
.org-name { font-size: 20px; font-weight: bold; color: #3730a3; letter-spacing: 1px; }
.org-sub { font-size: 10px; color: #6b7280; margin-top: 3px; }
.voucher-title { font-size: 14px; font-weight: bold; margin-top: 10px; color: #1f2937; }
.voucher-no { font-size: 10px; color: #6b7280; margin-top: 2px; }
table.details { width: 100%; border-collapse: collapse; margin-top: 10px; }
table.details td { padding: 8px 4px; border-bottom: 1px solid #f3f4f6; font-size: 12px; }
table.details td.label { color: #6b7280; width: 45%; }
table.details td.value { font-weight: bold; text-align: right; }
.amount-box { margin-top: 18px; background: #eef2ff; border: 1px solid #c7d2fe; border-radius: 6px; padding: 14px; text-align: center; }
.amount-label { font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; }
.amount-value { font-size: 24px; font-weight: bold; color: #3730a3; margin-top: 4px; }
.footer { margin-top: 24px; text-align: center; color: #9ca3af; font-size: 9px; border-top: 1px solid #e5e7eb; padding-top: 10px; }
.stamp { text-align: right; margin-top: 30px; font-size: 10px; color: #6b7280; }
</style>
</head>
<body>

<div class="voucher">
    <div class="header">
        <div class="org-name">Samite Platform</div>
        <div class="org-sub">Multi-Company SaaS Management</div>
        <div class="voucher-title">Payment Voucher</div>
        <div class="voucher-no">Voucher No: SP-{{ str_pad($subscription->id, 6, '0', STR_PAD_LEFT) }}</div>
    </div>

    <table class="details">
        <tr>
            <td class="label">Company</td>
            <td class="value">{{ $subscription->company->name ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Package</td>
            <td class="value">{{ $subscription->package->name ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Billing Period</td>
            <td class="value">{{ $subscription->starts_at->format('d M Y') }} &ndash; {{ $subscription->ends_at->format('d M Y') }}</td>
        </tr>
        <tr>
            <td class="label">Payment Method</td>
            <td class="value">{{ ucfirst($subscription->payment_method) }}</td>
        </tr>
        <tr>
            <td class="label">Payment Reference</td>
            <td class="value">{{ $subscription->payment_reference ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Activated By</td>
            <td class="value">{{ $subscription->activatedBy->name ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Date Issued</td>
            <td class="value">{{ $subscription->created_at?->format('d M Y, H:i') ?? '—' }}</td>
        </tr>
    </table>

    <div class="amount-box">
        <div class="amount-label">Amount Paid</div>
        <div class="amount-value">{{ number_format($subscription->amount, 2) }}</div>
    </div>

    <div class="stamp">Authorized Signature: ____________________</div>
</div>

<div class="footer">
    This is a system-generated voucher &mdash; Samite Platform &mdash; {{ now()->format('d M Y, H:i') }}
</div>
</body>
</html>
