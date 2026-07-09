<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Member-Wise Payment Report – Attonirbhor Joutho Uddyog</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;600;700&family=Noto+Sans:wght@400;600;700&display=swap" rel="stylesheet">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: 'Noto Sans Bengali', 'Noto Sans', Arial, sans-serif;
    font-size: 12px;
    color: #1f2937;
    background: #fff;
    padding: 32px 36px;
}
.no-print {
    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
    padding: 14px 20px; margin-bottom: 24px;
    display: flex; align-items: center; justify-content: space-between;
}
.no-print p { font-size: 13px; color: #64748b; }
.no-print .btn-print {
    background: #059669; color: #fff; border: none; cursor: pointer;
    padding: 9px 22px; border-radius: 8px; font-size: 13px; font-weight: 600;
    font-family: 'Noto Sans', Arial, sans-serif; margin-left: 10px;
}
.no-print .btn-close {
    background: #e5e7eb; color: #374151; border: none; cursor: pointer;
    padding: 9px 18px; border-radius: 8px; font-size: 13px;
    font-family: 'Noto Sans', Arial, sans-serif;
}
.rpt-header { border-bottom: 3px solid #059669; padding-bottom: 16px; margin-bottom: 20px; }
.rpt-header table { width: 100%; border-collapse: collapse; }
.rpt-header td { border: none; padding: 0; vertical-align: top; }
.org-name { font-size: 26px; font-weight: 700; color: #065f46; letter-spacing: 1px; font-family: 'Noto Sans', Arial, sans-serif; }
.org-sub { font-size: 11px; color: #6b7280; margin-top: 3px; }
.rpt-title { text-align: right; font-size: 18px; font-weight: 700; color: #111827; font-family: 'Noto Sans', Arial, sans-serif; }
.rpt-meta { text-align: right; font-size: 11px; color: #6b7280; margin-top: 3px; }
.member-section { margin-bottom: 18px; }
.member-header {
    background: #ecfdf5; padding: 8px 14px; border-left: 4px solid #059669;
    display: flex; justify-content: space-between; align-items: center;
}
.member-name { font-size: 14px; font-weight: 700; color: #065f46; font-family: 'Noto Sans', Arial, sans-serif; }
.member-info { font-size: 10px; color: #6b7280; }
table.data-tbl { width: 100%; border-collapse: collapse; }
table.data-tbl thead th {
    background: #065f46; color: #fff; padding: 7px 10px; text-align: left;
    font-size: 10px; font-weight: 600; font-family: 'Noto Sans', Arial, sans-serif;
}
table.data-tbl thead th.r { text-align: right; }
table.data-tbl tbody td { padding: 6px 10px; border-bottom: 1px solid #f3f4f6; font-size: 11px; }
table.data-tbl tbody td.r { text-align: right; }
table.data-tbl tbody tr:nth-child(even) td { background: #f9fafb; }
table.data-tbl tfoot td { padding: 7px 10px; font-weight: 700; background: #f0fdf4; border-top: 2px solid #059669; }
table.data-tbl tfoot td.r { text-align: right; }
.no-data { text-align: center; color: #9ca3af; padding: 24px; font-style: italic; }
.grand-total {
    background: #d1fae5; border: 2px solid #059669; border-radius: 8px;
    padding: 12px 18px; margin-top: 16px;
    display: flex; justify-content: space-between; align-items: center;
}
.grand-total .label { font-size: 14px; font-weight: 700; color: #065f46; font-family: 'Noto Sans', Arial, sans-serif; }
.grand-total .value { font-size: 18px; font-weight: 700; color: #059669; font-family: 'Noto Sans', Arial, sans-serif; }
.rpt-footer { margin-top: 32px; text-align: center; color: #9ca3af; font-size: 10px; border-top: 1px solid #e5e7eb; padding-top: 12px; }
.pay-method {
    display: inline-block; padding: 1px 8px; border-radius: 10px;
    font-size: 10px; font-weight: 600; font-family: 'Noto Sans', Arial, sans-serif;
}
.pay-cash { background: #fef3c7; color: #b45309; }
.pay-bkash { background: #fce7f3; color: #be185d; }
.pay-nagad { background: #ffedd5; color: #c2410c; }
.pay-bank { background: #dbeafe; color: #1d4ed8; }
@media print {
    .no-print { display: none !important; }
    body { padding: 16px 20px; }
    @page { margin: 12mm 15mm; size: A4 landscape; }
}
</style>
</head>
<body>

<div class="no-print">
    <p>&#x1F4C4; Click <strong>Print / Save as PDF</strong> &rarr; select <strong>Save as PDF</strong> in the print dialog.</p>
    <div>
        <button class="btn-close" onclick="window.close()">&#x2715; Close</button>
        <button class="btn-print" onclick="window.print()">&#x1F5A8; Print / Save as PDF</button>
    </div>
</div>

<div class="rpt-header">
    <table>
        <tr>
            <td>
                <div class="org-name">Attonirbhor Joutho Uddyog</div>
                <div class="org-sub">Group Savings &amp; Expense Management System</div>
            </td>
            <td style="text-align:right;">
                <div class="rpt-title">Member-Wise Payment Report</div>
                @if($selectedMember)
                <div class="rpt-meta">Member: {{ $selectedMember->user->name }} ({{ $selectedMember->member_code }})</div>
                @endif
                <div class="rpt-meta">
                    Period:
                    @if($from && $to) {{ $from }} to {{ $to }}
                    @elseif($from) From {{ $from }}
                    @elseif($to) Up to {{ $to }}
                    @else All Time
                    @endif
                </div>
                <div class="rpt-meta">Generated: {{ now()->format('d M Y, H:i') }}</div>
            </td>
        </tr>
    </table>
</div>

@forelse($members as $group)
@php $m = $group->member; @endphp
<div class="member-section">
    <div class="member-header">
        <div>
            <span class="member-name">{{ $m->user->name }}</span>
            <span class="member-info">
                &nbsp;| Code: {{ $m->member_code }} &middot; Phone: {{ $m->phone ?? '—' }}
            </span>
        </div>
        <span style="font-size:12px;font-weight:700;color:#059669;font-family:'Noto Sans',Arial,sans-serif;">
            Total: BDT {{ number_format($group->total, 2) }}
        </span>
    </div>
    <table class="data-tbl">
        <thead>
            <tr>
                <th style="width:40px;">#</th>
                <th>Date</th>
                <th class="r">Amount (BDT)</th>
                <th>Payment Method</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($group->deposits as $i => $d)
            <tr>
                <td style="font-family:'Noto Sans',Arial,sans-serif;">{{ $i + 1 }}</td>
                <td>{{ $d->date->format('d M Y') }}</td>
                <td class="r" style="font-weight:600;color:#059669;font-family:'Noto Sans',Arial,sans-serif;">{{ number_format($d->amount, 2) }}</td>
                <td>
                    <span class="pay-method pay-{{ $d->payment_method ?? 'cash' }}">
                        {{ ucfirst($d->payment_method ?? '—') }}
                    </span>
                </td>
                <td style="color:#6b7280;">{{ $d->notes ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align:right;font-family:'Noto Sans',Arial,sans-serif;">Subtotal:</td>
                <td class="r" style="font-family:'Noto Sans',Arial,sans-serif;">{{ number_format($group->total, 2) }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</div>
@empty
<p class="no-data">No payment data found for the selected period.</p>
@endforelse

@if($grandTotal > 0)
<div class="grand-total">
    <span class="label">TOTAL</span>
    <span class="value">BDT {{ number_format($grandTotal, 2) }}</span>
</div>
@endif

<div class="rpt-footer">
    Attonirbhor Joutho Uddyog &mdash; Confidential Financial Report &mdash; {{ now()->format('d M Y, H:i') }}
</div>

<script>
document.fonts.ready.then(function () {
    setTimeout(function () { window.print(); }, 600);
});
</script>
</body>
</html>
