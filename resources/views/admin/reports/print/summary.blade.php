<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Full Summary Report – SUMITY</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;600;700&family=Noto+Sans:wght@400;600;700&display=swap" rel="stylesheet">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: 'Noto Sans Bengali', 'Noto Sans', Arial, sans-serif;
    font-size: 12px; color: #1f2937; background: #fff; padding: 28px 32px;
}
.no-print {
    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
    padding: 12px 18px; margin-bottom: 22px;
    display: flex; align-items: center; justify-content: space-between;
}
.no-print p { font-size: 12px; color: #64748b; }
.no-print .btn-print {
    background: #059669; color: #fff; border: none; cursor: pointer;
    padding: 8px 20px; border-radius: 8px; font-size: 12px; font-weight: 600;
    font-family: 'Noto Sans', Arial, sans-serif; margin-left: 8px;
}
.no-print .btn-close {
    background: #e5e7eb; color: #374151; border: none; cursor: pointer;
    padding: 8px 16px; border-radius: 8px; font-size: 12px;
    font-family: 'Noto Sans', Arial, sans-serif;
}

/* Header */
.rpt-header { border-bottom: 3px solid #059669; padding-bottom: 14px; margin-bottom: 18px; }
.rpt-header table { width: 100%; border-collapse: collapse; }
.rpt-header td { border: none; padding: 0; vertical-align: top; }
.org-name { font-size: 24px; font-weight: 700; color: #065f46; letter-spacing: 1px; font-family: 'Noto Sans', Arial, sans-serif; }
.org-sub { font-size: 10px; color: #6b7280; margin-top: 2px; }
.rpt-title { text-align: right; font-size: 17px; font-weight: 700; color: #111827; font-family: 'Noto Sans', Arial, sans-serif; }
.rpt-meta { text-align: right; font-size: 10px; color: #6b7280; margin-top: 2px; }

/* Summary */
.summary-bar { border: 1px solid #d1fae5; border-radius: 8px; margin-bottom: 20px; }
.summary-bar table { width: 100%; border-collapse: collapse; }
.summary-bar td { padding: 10px 16px; border-right: 1px solid #d1fae5; vertical-align: middle; }
.summary-bar td:last-child { border-right: none; }
.sum-label { font-size: 10px; color: #6b7280; font-family: 'Noto Sans', Arial, sans-serif; }
.sum-val { font-size: 15px; font-weight: 700; margin-top: 2px; font-family: 'Noto Sans', Arial, sans-serif; }
.income { color: #059669; }
.expense { color: #dc2626; }

/* Section title */
.sec-title {
    font-size: 11px; font-weight: 700; color: #065f46;
    background: #ecfdf5; padding: 7px 12px; border-left: 4px solid #059669;
    margin-bottom: 0; font-family: 'Noto Sans', Arial, sans-serif;
}

/* Tables */
table.data-tbl { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
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

/* Matrix table */
table.matrix-tbl { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 10px; }
table.matrix-tbl thead th {
    background: #1f2937; color: #fff; padding: 6px 8px;
    font-weight: 600; font-family: 'Noto Sans', Arial, sans-serif;
    border: 1px solid #374151;
}
table.matrix-tbl thead th.c { text-align: center; }
table.matrix-tbl thead th.r { text-align: right; }
table.matrix-tbl tbody td { padding: 5px 8px; border: 1px solid #e5e7eb; }
table.matrix-tbl tbody td.r { text-align: right; }
table.matrix-tbl tbody td.amt { text-align: right; font-family: 'Noto Sans', Arial, sans-serif; color: #059669; font-weight: 600; }
table.matrix-tbl tbody td.zero { text-align: right; color: #d1d5db; }
table.matrix-tbl tbody tr:nth-child(even) td { background: #fafafa; }
table.matrix-tbl tfoot td { padding: 6px 8px; font-weight: 700; background: #ecfdf5; border: 1px solid #059669; font-family: 'Noto Sans', Arial, sans-serif; color: #065f46; }
table.matrix-tbl tfoot td.r { text-align: right; }

.no-data { text-align: center; color: #9ca3af; padding: 20px; font-style: italic; }
.rpt-footer { margin-top: 28px; text-align: center; color: #9ca3af; font-size: 9px; border-top: 1px solid #e5e7eb; padding-top: 10px; }

@media print {
    .no-print { display: none !important; }
    body { padding: 12px 16px; font-size: 11px; }
    @page { margin: 10mm 12mm; size: A4 landscape; }
}
</style>
</head>
<body>

<div class="no-print">
    <p>&#x1F4C4; Click <strong>Print / Save as PDF</strong> &rarr; select <strong>Save as PDF</strong> in the print dialog. Recommend <strong>Landscape</strong> orientation.</p>
    <div>
        <button class="btn-close" onclick="window.close()">&#x2715; Close</button>
        <button class="btn-print" onclick="window.print()">&#x1F5A8; Print / Save as PDF</button>
    </div>
</div>

<!-- Header -->
<div class="rpt-header">
    <table>
        <tr>
            <td>
                <div class="org-name">SUMITY</div>
                <div class="org-sub">Group Savings &amp; Expense Management System</div>
            </td>
            <td style="text-align:right;">
                <div class="rpt-title">Full Summary Report</div>
                <div class="rpt-meta">Period: {{ $startDate->format('F Y') }} &ndash; {{ $endDate->format('F Y') }}</div>
                <div class="rpt-meta">Generated: {{ now()->format('d M Y, H:i') }}</div>
            </td>
        </tr>
    </table>
</div>

<!-- Summary -->
<div class="summary-bar">
    <table>
        <tr style="background:#f0fdf4;">
            <td><div class="sum-label">Total Collected</div><div class="sum-val income">BDT {{ number_format($totalIncome, 2) }}</div></td>
            <td><div class="sum-label">Total Expenses</div><div class="sum-val expense">BDT {{ number_format($totalExpense, 2) }}</div></td>
            <td><div class="sum-label">Net Savings</div><div class="sum-val" style="color:#1d4ed8;">BDT {{ number_format($totalIncome - $totalExpense, 2) }}</div></td>
            <td><div class="sum-label">Fund Balance</div><div class="sum-val" style="color:#7c3aed;">BDT {{ number_format($balance, 2) }}</div></td>
            <td><div class="sum-label">Period</div><div class="sum-val" style="font-size:12px;color:#374151;">{{ count($months) }} months</div></td>
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
        @foreach($months as $label)
        @php $inc = $monthlyTotals[$label]['income'] ?? 0; $exp = $monthlyTotals[$label]['expense'] ?? 0; $net = $inc - $exp; @endphp
        <tr>
            <td>{{ $label }}</td>
            <td class="r income" style="font-family:'Noto Sans',Arial,sans-serif;">{{ $inc > 0 ? number_format($inc, 2) : '—' }}</td>
            <td class="r expense" style="font-family:'Noto Sans',Arial,sans-serif;">{{ $exp > 0 ? number_format($exp, 2) : '—' }}</td>
            <td class="r" style="font-weight:700;font-family:'Noto Sans',Arial,sans-serif;color:{{ $net >= 0 ? '#059669' : '#dc2626' }};">{{ $net != 0 ? number_format($net, 2) : '—' }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td>Grand Total</td>
            <td class="r income" style="font-family:'Noto Sans',Arial,sans-serif;">{{ number_format($totalIncome, 2) }}</td>
            <td class="r expense" style="font-family:'Noto Sans',Arial,sans-serif;">{{ number_format($totalExpense, 2) }}</td>
            <td class="r" style="color:#1d4ed8;font-family:'Noto Sans',Arial,sans-serif;">{{ number_format($totalIncome - $totalExpense, 2) }}</td>
        </tr>
    </tfoot>
</table>

<!-- Member × Month Matrix -->
<div class="sec-title">Member Deposit Matrix — Month-wise (BDT)</div>
<table class="matrix-tbl">
    <thead>
        <tr>
            <th style="text-align:left;min-width:130px;">Member Name</th>
            <th class="c" style="min-width:60px;">Code</th>
            @foreach($months as $label)
            <th class="r" style="min-width:72px;">{{ $label }}</th>
            @endforeach
            <th class="r" style="background:#374151;min-width:78px;">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($members as $m)
        @php $memberTotal = collect($months)->sum(fn($label) => $matrix[$m->id][$label] ?? 0); @endphp
        <tr>
            <td>{{ $m->user->name }}</td>
            <td style="text-align:center;font-family:'Noto Sans',Arial,sans-serif;color:#6b7280;font-size:9px;">{{ $m->member_code }}</td>
            @foreach($months as $label)
            @php $amt = $matrix[$m->id][$label] ?? 0; @endphp
            @if($amt > 0)
            <td class="amt">{{ number_format($amt, 0) }}</td>
            @else
            <td class="zero">—</td>
            @endif
            @endforeach
            <td class="r" style="font-weight:700;font-family:'Noto Sans',Arial,sans-serif;background:#f0fdf4;color:#065f46;">{{ $memberTotal > 0 ? number_format($memberTotal, 0) : '—' }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td>Monthly Total</td>
            <td></td>
            @foreach($months as $label)
            <td class="r">{{ number_format($monthlyTotals[$label]['income'] ?? 0, 0) }}</td>
            @endforeach
            <td class="r">{{ number_format($totalIncome, 0) }}</td>
        </tr>
    </tfoot>
</table>

<!-- Expense Breakdown -->
@if($expenseBreakdown->count())
<div class="sec-title">Expense Breakdown by Category</div>
<table class="data-tbl" style="max-width:400px;">
    <thead>
        <tr><th>Category</th><th class="r">Total (BDT)</th></tr>
    </thead>
    <tbody>
        @foreach($expenseBreakdown as $e)
        <tr>
            <td>{{ \App\Models\Expense::categories()[$e->category] ?? ucfirst($e->category) }}</td>
            <td class="r expense" style="font-family:'Noto Sans',Arial,sans-serif;">{{ number_format($e->total, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot style="background:#fef2f2;">
        <tr>
            <td>Total Expenses</td>
            <td class="r expense" style="font-family:'Noto Sans',Arial,sans-serif;">{{ number_format($totalExpense, 2) }}</td>
        </tr>
    </tfoot>
</table>
@endif

<div class="rpt-footer">
    SUMITY &mdash; Confidential Full Summary Report &mdash; {{ now()->format('d M Y, H:i') }}
</div>

<script>
document.fonts.ready.then(function () {
    setTimeout(function () { window.print(); }, 700);
});
</script>
</body>
</html>
