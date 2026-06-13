<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Yearly Report – {{ $year }} – SUMITY</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;600;700&family=Noto+Sans:wght@400;600;700&display=swap" rel="stylesheet">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: 'Noto Sans Bengali', 'Noto Sans', Arial, sans-serif;
    font-size: 13px; color: #1f2937; background: #fff; padding: 32px 36px;
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
.summary-bar { border: 1px solid #d1fae5; border-radius: 8px; margin-bottom: 22px; overflow: hidden; }
.summary-bar table { width: 100%; border-collapse: collapse; }
.summary-bar td { padding: 12px 18px; border-right: 1px solid #d1fae5; vertical-align: middle; }
.summary-bar td:last-child { border-right: none; }
.sum-label { font-size: 11px; color: #6b7280; font-family: 'Noto Sans', Arial, sans-serif; }
.sum-val { font-size: 16px; font-weight: 700; margin-top: 2px; font-family: 'Noto Sans', Arial, sans-serif; }
.income { color: #059669; }
.expense { color: #dc2626; }
.sec-title {
    font-size: 12px; font-weight: 700; color: #065f46;
    background: #ecfdf5; padding: 8px 14px; border-left: 4px solid #059669;
    margin-bottom: 0; font-family: 'Noto Sans', Arial, sans-serif;
}
table.data-tbl { width: 100%; border-collapse: collapse; margin-bottom: 22px; }
table.data-tbl thead th {
    background: #065f46; color: #fff; padding: 9px 12px; text-align: left;
    font-size: 11px; font-weight: 600; font-family: 'Noto Sans', Arial, sans-serif;
}
table.data-tbl thead th.r { text-align: right; }
table.data-tbl tbody td { padding: 8px 12px; border-bottom: 1px solid #f3f4f6; font-size: 12px; }
table.data-tbl tbody td.r { text-align: right; }
table.data-tbl tbody tr:nth-child(even) td { background: #f9fafb; }
table.data-tbl tfoot td { padding: 9px 12px; font-weight: 700; background: #f0fdf4; border-top: 2px solid #059669; }
table.data-tbl tfoot td.r { text-align: right; }
.no-data { text-align: center; color: #9ca3af; padding: 24px; font-style: italic; }
.col-half { display: inline-block; width: 49%; vertical-align: top; }
.rpt-footer { margin-top: 32px; text-align: center; color: #9ca3af; font-size: 10px; border-top: 1px solid #e5e7eb; padding-top: 12px; }
@media print {
    .no-print { display: none !important; }
    body { padding: 16px 20px; }
    @page { margin: 12mm 15mm; size: A4 portrait; }
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
                <div class="org-name">SUMITY</div>
                <div class="org-sub">Group Savings &amp; Expense Management System</div>
            </td>
            <td style="text-align:right;">
                <div class="rpt-title">Yearly Financial Report – {{ $year }}</div>
                <div class="rpt-meta">Generated: {{ now()->format('d M Y, H:i') }}</div>
            </td>
        </tr>
    </table>
</div>

<div class="summary-bar">
    <table>
        <tr style="background:#f0fdf4;">
            <td><div class="sum-label">Total Income {{ $year }}</div><div class="sum-val income">BDT {{ number_format($totalIncome, 2) }}</div></td>
            <td><div class="sum-label">Total Expense {{ $year }}</div><div class="sum-val expense">BDT {{ number_format($totalExpense, 2) }}</div></td>
            <td><div class="sum-label">Net Savings</div><div class="sum-val" style="color:#7c3aed;">BDT {{ number_format($totalIncome - $totalExpense, 2) }}</div></td>
        </tr>
    </table>
</div>

<!-- Monthly Breakdown -->
<div class="sec-title">Monthly Income vs Expense</div>
<table class="data-tbl">
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
            <td class="r income" style="font-family:'Noto Sans',Arial,sans-serif;">{{ $md['income'] > 0 ? number_format($md['income'], 2) : '—' }}</td>
            <td class="r expense" style="font-family:'Noto Sans',Arial,sans-serif;">{{ $md['expense'] > 0 ? number_format($md['expense'], 2) : '—' }}</td>
            <td class="r" style="font-weight:700;font-family:'Noto Sans',Arial,sans-serif;color:{{ $net >= 0 ? '#059669' : '#dc2626' }};">{{ $net != 0 ? number_format($net, 2) : '—' }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td style="font-family:'Noto Sans',Arial,sans-serif;">Annual Total</td>
            <td class="r income" style="font-family:'Noto Sans',Arial,sans-serif;">{{ number_format($totalIncome, 2) }}</td>
            <td class="r expense" style="font-family:'Noto Sans',Arial,sans-serif;">{{ number_format($totalExpense, 2) }}</td>
            <td class="r" style="color:#7c3aed;font-family:'Noto Sans',Arial,sans-serif;">{{ number_format($totalIncome - $totalExpense, 2) }}</td>
        </tr>
    </tfoot>
</table>

<!-- Member Ranking -->
<div class="sec-title">Member Contribution Ranking</div>
<table class="data-tbl">
    <thead>
        <tr>
            <th style="width:50px;">Rank</th>
            <th>Member Name</th>
            <th>Member Code</th>
            <th class="r">Total Deposited (BDT)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($memberContributions as $i => $m)
        <tr>
            <td style="font-family:'Noto Sans',Arial,sans-serif;">{{ $i + 1 }}</td>
            <td>{{ $m->user->name }}</td>
            <td style="font-family:'Noto Sans',Arial,sans-serif;font-size:11px;color:#6b7280;">{{ $m->member_code }}</td>
            <td class="r income" style="font-weight:700;font-family:'Noto Sans',Arial,sans-serif;">{{ $m->year_total > 0 ? number_format($m->year_total, 2) : '—' }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align:right;font-family:'Noto Sans',Arial,sans-serif;">Total:</td>
            <td class="r income" style="font-family:'Noto Sans',Arial,sans-serif;">{{ number_format($totalIncome, 2) }}</td>
        </tr>
    </tfoot>
</table>

<!-- Expense by Category -->
<div class="sec-title">Expense by Category</div>
<table class="data-tbl">
    <thead>
        <tr><th>Category</th><th class="r">Total (BDT)</th></tr>
    </thead>
    <tbody>
        @forelse($expenseTrends as $et)
        <tr>
            <td>{{ \App\Models\Expense::categories()[$et->category] ?? ucfirst($et->category) }}</td>
            <td class="r expense" style="font-weight:700;font-family:'Noto Sans',Arial,sans-serif;">{{ number_format($et->total, 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="2" class="no-data">No expenses for {{ $year }}</td></tr>
        @endforelse
    </tbody>
    @if($expenseTrends->count())
    <tfoot style="background:#fef2f2;">
        <tr>
            <td style="text-align:right;font-family:'Noto Sans',Arial,sans-serif;">Total Expense:</td>
            <td class="r expense" style="font-family:'Noto Sans',Arial,sans-serif;">{{ number_format($totalExpense, 2) }}</td>
        </tr>
    </tfoot>
    @endif
</table>

<div class="rpt-footer">
    SUMITY &mdash; Confidential Financial Report &mdash; {{ now()->format('d M Y, H:i') }}
</div>

<script>
document.fonts.ready.then(function () {
    setTimeout(function () { window.print(); }, 600);
});
</script>
</body>
</html>
