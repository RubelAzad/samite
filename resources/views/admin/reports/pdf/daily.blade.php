<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #1f2937; background: #fff; padding: 24px; }
.header { border-bottom: 3px solid #059669; padding-bottom: 14px; margin-bottom: 18px; }
.org-name { font-size: 22px; font-weight: bold; color: #065f46; letter-spacing: 1px; }
.org-sub { font-size: 10px; color: #6b7280; margin-top: 2px; }
.report-title { font-size: 14px; font-weight: bold; color: #1f2937; text-align: right; }
.report-meta { font-size: 10px; color: #6b7280; text-align: right; margin-top: 3px; }
.summary { width: 100%; border-collapse: collapse; margin-bottom: 18px; border: 1px solid #d1fae5; }
.summary td { padding: 9px 14px; }
.summary .label { color: #6b7280; font-size: 10px; }
.summary .value { font-weight: bold; font-size: 13px; }
.income { color: #059669; }
.expense { color: #dc2626; }
.net-positive { color: #1d4ed8; }
.section-title { font-size: 12px; font-weight: bold; color: #065f46; background: #ecfdf5; padding: 7px 12px; border-left: 4px solid #059669; margin-bottom: 0; }
table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
thead th { background: #065f46; color: #fff; padding: 8px 10px; text-align: left; font-size: 10px; font-weight: bold; }
thead th.r { text-align: right; }
tbody td { padding: 7px 10px; border-bottom: 1px solid #f3f4f6; font-size: 11px; }
tbody td.r { text-align: right; }
tbody tr:nth-child(even) td { background: #f9fafb; }
tfoot td { padding: 8px 10px; font-weight: bold; background: #f0fdf4; border-top: 2px solid #059669; }
tfoot td.r { text-align: right; }
.no-data { text-align: center; color: #9ca3af; padding: 18px; font-style: italic; }
.footer { margin-top: 28px; text-align: center; color: #9ca3af; font-size: 9px; border-top: 1px solid #e5e7eb; padding-top: 10px; }
</style>
</head>
<body>

<table class="header" style="width:100%;border:none;">
    <tr>
        <td style="border:none;padding:0;">
            <div class="org-name">SUMITY</div>
            <div class="org-sub">Group Savings &amp; Expense Management System</div>
        </td>
        <td style="border:none;padding:0;text-align:right;vertical-align:top;">
            <div class="report-title">Daily Financial Report</div>
            <div class="report-meta">Date: {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</div>
            <div class="report-meta">Generated: {{ now()->format('d M Y, H:i') }}</div>
        </td>
    </tr>
</table>

{{-- Summary --}}
<table class="summary">
    <tr style="background:#f0fdf4;">
        <td class="label">Total Income</td>
        <td class="value income">BDT {{ number_format($totalIncome, 2) }}</td>
        <td class="label">Total Expense</td>
        <td class="value expense">BDT {{ number_format($totalExpense, 2) }}</td>
        <td class="label">Net</td>
        <td class="value net-positive">BDT {{ number_format($net, 2) }}</td>
        <td class="label">Deposits</td>
        <td class="value">{{ $deposits->count() }}</td>
        <td class="label">Expenses</td>
        <td class="value">{{ $expenses->count() }}</td>
    </tr>
</table>

{{-- Deposits --}}
<div class="section-title">Deposits ({{ $deposits->count() }})</div>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Member Name</th>
            <th>Member Code</th>
            <th class="r">Amount (BDT)</th>
            <th>Payment Method</th>
        </tr>
    </thead>
    <tbody>
        @forelse($deposits as $i => $d)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $d->member->user->name ?? 'N/A' }}</td>
            <td>{{ $d->member->member_code ?? '—' }}</td>
            <td class="r income" style="font-weight:bold;">{{ number_format($d->amount, 2) }}</td>
            <td>{{ ucfirst($d->payment_method) }}</td>
        </tr>
        @empty
        <tr><td colspan="5" class="no-data">No deposits recorded for this date</td></tr>
        @endforelse
    </tbody>
    @if($deposits->count())
    <tfoot>
        <tr>
            <td colspan="3" style="text-align:right;">Total Income:</td>
            <td class="r income">{{ number_format($totalIncome, 2) }}</td>
            <td></td>
        </tr>
    </tfoot>
    @endif
</table>

{{-- Expenses --}}
<div class="section-title">Expenses ({{ $expenses->count() }})</div>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Category</th>
            <th class="r">Amount (BDT)</th>
            <th>Notes</th>
        </tr>
    </thead>
    <tbody>
        @forelse($expenses as $i => $e)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $e->title }}</td>
            <td>{{ ucfirst($e->category) }}</td>
            <td class="r expense" style="font-weight:bold;">{{ number_format($e->amount, 2) }}</td>
            <td style="color:#6b7280;">{{ $e->notes ?? '—' }}</td>
        </tr>
        @empty
        <tr><td colspan="5" class="no-data">No expenses recorded for this date</td></tr>
        @endforelse
    </tbody>
    @if($expenses->count())
    <tfoot>
        <tr style="background:#fef2f2;">
            <td colspan="3" style="text-align:right;">Total Expense:</td>
            <td class="r expense">{{ number_format($totalExpense, 2) }}</td>
            <td></td>
        </tr>
    </tfoot>
    @endif
</table>

<div class="footer">
    SUMITY &mdash; Confidential Financial Report &mdash; {{ now()->format('d M Y, H:i') }}
</div>
</body>
</html>
