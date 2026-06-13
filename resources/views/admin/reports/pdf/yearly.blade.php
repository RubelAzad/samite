<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #1f2937; background: #fff; padding: 24px; }
.org-name { font-size: 22px; font-weight: bold; color: #065f46; letter-spacing: 1px; }
.org-sub { font-size: 10px; color: #6b7280; margin-top: 2px; }
.report-title { font-size: 14px; font-weight: bold; color: #1f2937; text-align: right; }
.report-meta { font-size: 10px; color: #6b7280; text-align: right; margin-top: 3px; }
.summary { width: 100%; border-collapse: collapse; margin: 16px 0; border: 1px solid #d1fae5; }
.summary td { padding: 8px 12px; }
.summary .label { color: #6b7280; font-size: 10px; }
.summary .value { font-weight: bold; font-size: 13px; }
.income { color: #059669; }
.expense { color: #dc2626; }
.section-title { font-size: 12px; font-weight: bold; color: #065f46; background: #ecfdf5; padding: 7px 12px; border-left: 4px solid #059669; margin-bottom: 0; }
table.data { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
table.data thead th { background: #065f46; color: #fff; padding: 8px 10px; text-align: left; font-size: 10px; font-weight: bold; }
table.data thead th.r { text-align: right; }
table.data tbody td { padding: 7px 10px; border-bottom: 1px solid #f3f4f6; }
table.data tbody td.r { text-align: right; }
table.data tbody tr:nth-child(even) td { background: #f9fafb; }
table.data tfoot td { padding: 8px 10px; font-weight: bold; background: #f0fdf4; border-top: 2px solid #059669; }
table.data tfoot td.r { text-align: right; }
.no-data { text-align: center; color: #9ca3af; padding: 18px; font-style: italic; }
.footer { margin-top: 28px; text-align: center; color: #9ca3af; font-size: 9px; border-top: 1px solid #e5e7eb; padding-top: 10px; }
.page-break { page-break-before: always; }
</style>
</head>
<body>

<table style="width:100%;border:none;border-bottom:3px solid #059669;padding-bottom:14px;margin-bottom:16px;">
    <tr>
        <td style="border:none;padding:0;">
            <div class="org-name">SUMITY</div>
            <div class="org-sub">Group Savings &amp; Expense Management System</div>
        </td>
        <td style="border:none;padding:0;text-align:right;vertical-align:top;">
            <div class="report-title">Yearly Financial Report – {{ $year }}</div>
            <div class="report-meta">Generated: {{ now()->format('d M Y, H:i') }}</div>
        </td>
    </tr>
</table>

{{-- Summary --}}
<table class="summary">
    <tr style="background:#f0fdf4;">
        <td class="label">Total Income {{ $year }}</td>
        <td class="value income">BDT {{ number_format($totalIncome, 2) }}</td>
        <td class="label">Total Expense {{ $year }}</td>
        <td class="value expense">BDT {{ number_format($totalExpense, 2) }}</td>
        <td class="label">Net Savings</td>
        <td class="value" style="color:#7c3aed;">BDT {{ number_format($totalIncome - $totalExpense, 2) }}</td>
    </tr>
</table>

{{-- Monthly Breakdown --}}
<div class="section-title">Monthly Income vs Expense</div>
<table class="data">
    <thead>
        <tr>
            <th>Month</th>
            <th class="r">Income (BDT)</th>
            <th class="r">Expense (BDT)</th>
            <th class="r">Net (BDT)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($monthlyData as $md)
        @php $net = $md['income'] - $md['expense']; @endphp
        <tr>
            <td>{{ $md['month'] }}</td>
            <td class="r income">{{ $md['income'] > 0 ? number_format($md['income'], 2) : '—' }}</td>
            <td class="r expense">{{ $md['expense'] > 0 ? number_format($md['expense'], 2) : '—' }}</td>
            <td class="r" style="font-weight:bold;color:{{ $net >= 0 ? '#059669' : '#dc2626' }};">
                {{ $net != 0 ? number_format($net, 2) : '—' }}
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td style="font-weight:bold;">Annual Total</td>
            <td class="r income">{{ number_format($totalIncome, 2) }}</td>
            <td class="r expense">{{ number_format($totalExpense, 2) }}</td>
            <td class="r" style="color:#7c3aed;font-weight:bold;">{{ number_format($totalIncome - $totalExpense, 2) }}</td>
        </tr>
    </tfoot>
</table>

{{-- Member Ranking --}}
<div class="section-title">Member Contribution Ranking</div>
<table class="data">
    <thead>
        <tr>
            <th>Rank</th>
            <th>Member Name</th>
            <th>Member Code</th>
            <th class="r">Total Deposited (BDT)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($memberContributions as $i => $m)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $m->user->name }}</td>
            <td>{{ $m->member_code }}</td>
            <td class="r income" style="font-weight:bold;">{{ $m->year_total > 0 ? number_format($m->year_total, 2) : '—' }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align:right;">Total:</td>
            <td class="r income">{{ number_format($totalIncome, 2) }}</td>
        </tr>
    </tfoot>
</table>

{{-- Expense by Category --}}
<div class="section-title">Expense by Category</div>
<table class="data">
    <thead>
        <tr>
            <th>Category</th>
            <th class="r">Total (BDT)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($expenseTrends as $et)
        <tr>
            <td>{{ \App\Models\Expense::categories()[$et->category] ?? ucfirst($et->category) }}</td>
            <td class="r expense" style="font-weight:bold;">{{ number_format($et->total, 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="2" class="no-data">No expenses for {{ $year }}</td></tr>
        @endforelse
    </tbody>
    @if($expenseTrends->count())
    <tfoot style="background:#fef2f2;">
        <tr>
            <td style="text-align:right;">Total Expense:</td>
            <td class="r expense">{{ number_format($totalExpense, 2) }}</td>
        </tr>
    </tfoot>
    @endif
</table>

<div class="footer">
    SUMITY &mdash; Confidential Financial Report &mdash; {{ now()->format('d M Y, H:i') }}
</div>
</body>
</html>
