<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 10px; color: #1f2937; background: #fff; padding: 20px; }
.org-name { font-size: 20px; font-weight: bold; color: #065f46; letter-spacing: 1px; }
.org-sub { font-size: 9px; color: #6b7280; margin-top: 2px; }
.report-title { font-size: 13px; font-weight: bold; color: #1f2937; text-align: right; }
.report-meta { font-size: 9px; color: #6b7280; text-align: right; margin-top: 2px; }
.member-sec { margin-bottom: 14px; }
.member-head { background: #ecfdf5; padding: 6px 10px; border-left: 4px solid #059669; margin-bottom: 0; }
.member-head .name { font-size: 12px; font-weight: bold; color: #065f46; }
.member-head .info { font-size: 9px; color: #6b7280; }
.member-head .total { float: right; font-size: 11px; font-weight: bold; color: #059669; }
table.data { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
table.data thead th { background: #065f46; color: #fff; padding: 6px 8px; text-align: left; font-size: 9px; font-weight: bold; }
table.data thead th.r { text-align: right; }
table.data tbody td { padding: 5px 8px; border-bottom: 1px solid #f3f4f6; }
table.data tbody td.r { text-align: right; }
table.data tbody tr:nth-child(even) td { background: #f9fafb; }
table.data tfoot td { padding: 6px 8px; font-weight: bold; background: #f0fdf4; border-top: 2px solid #059669; }
table.data tfoot td.r { text-align: right; }
.no-data { text-align: center; color: #9ca3af; padding: 18px; font-style: italic; }
.grand { background: #d1fae5; border: 2px solid #059669; padding: 10px 14px; margin-top: 12px; text-align: right; }
.grand .label { font-size: 12px; font-weight: bold; color: #065f46; }
.grand .value { font-size: 15px; font-weight: bold; color: #059669; margin-left: 16px; }
.footer { margin-top: 24px; text-align: center; color: #9ca3af; font-size: 8px; border-top: 1px solid #e5e7eb; padding-top: 10px; }
</style>
</head>
<body>

<table style="width:100%;border:none;border-bottom:3px solid #059669;padding-bottom:12px;margin-bottom:14px;">
    <tr>
        <td style="border:none;padding:0;">
            <div class="org-name">Attonirbhor Joutho Uddyog</div>
            <div class="org-sub">Group Savings &amp; Expense Management System</div>
        </td>
        <td style="border:none;padding:0;text-align:right;vertical-align:top;">
            <div class="report-title">Member-Wise Payment Report</div>
            @if($selectedMember)
            <div class="report-meta">Member: {{ $selectedMember->user->name }} ({{ $selectedMember->member_code }})</div>
            @endif
            <div class="report-meta">
                Period:
                @if($from && $to) {{ $from }} to {{ $to }}
                @elseif($from) From {{ $from }}
                @elseif($to) Up to {{ $to }}
                @else All Time
                @endif
            </div>
            <div class="report-meta">Generated: {{ now()->format('d M Y H:i') }}</div>
        </td>
    </tr>
</table>

@forelse($members as $group)
@php $m = $group->member; @endphp
<div class="member-sec">
    <div class="member-head">
        <span class="name">{{ $m->user->name }}</span>
        <span class="info">Code: {{ $m->member_code }} &middot; Phone: {{ $m->phone ?? '—' }}</span>
        <span class="total">Total: BDT {{ number_format($group->total, 2) }}</span>
    </div>
    <table class="data">
        <thead>
            <tr>
                <th style="width:32px;">#</th>
                <th>Date</th>
                <th class="r">Amount (BDT)</th>
                <th>Method</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($group->deposits as $i => $d)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $d->date->format('d M Y') }}</td>
                <td class="r" style="font-weight:bold;color:#059669;">{{ number_format($d->amount, 2) }}</td>
                <td>{{ ucfirst($d->payment_method ?? '—') }}</td>
                <td style="color:#6b7280;">{{ $d->notes ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align:right;">Subtotal:</td>
                <td class="r">{{ number_format($group->total, 2) }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</div>
@empty
<p class="no-data">No payment data found for the selected period.</p>
@endforelse

@if($grandTotal > 0)
<div class="grand">
    <span class="label">TOTAL:</span>
    <span class="value">BDT {{ number_format($grandTotal, 2) }}</span>
</div>
@endif

<div class="footer">
    Attonirbhor Joutho Uddyog &mdash; Confidential Financial Report &mdash; {{ now()->format('d M Y H:i') }}
</div>
</body>
</html>
