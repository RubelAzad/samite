<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daily Report – {{ \Carbon\Carbon::parse($date)->format('d M Y') }} – SUMITY</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;600;700&family=Noto+Sans:wght@400;600;700&display=swap" rel="stylesheet">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: 'Noto Sans Bengali', 'Noto Sans', Arial, sans-serif;
    font-size: 13px;
    color: #1f2937;
    background: #fff;
    padding: 32px 36px;
}

/* No-print controls */
.no-print {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 14px 20px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.no-print p { font-size: 13px; color: #64748b; }
.no-print .btn-print {
    background: #059669; color: #fff;
    border: none; cursor: pointer;
    padding: 9px 22px; border-radius: 8px;
    font-size: 13px; font-weight: 600;
    font-family: 'Noto Sans', Arial, sans-serif;
    margin-left: 10px;
}
.no-print .btn-close {
    background: #e5e7eb; color: #374151;
    border: none; cursor: pointer;
    padding: 9px 18px; border-radius: 8px;
    font-size: 13px;
    font-family: 'Noto Sans', Arial, sans-serif;
}

/* Report header */
.rpt-header { border-bottom: 3px solid #059669; padding-bottom: 16px; margin-bottom: 20px; }
.rpt-header table { width: 100%; border-collapse: collapse; }
.rpt-header td { border: none; padding: 0; vertical-align: top; }
.org-name { font-size: 26px; font-weight: 700; color: #065f46; letter-spacing: 1px; font-family: 'Noto Sans', Arial, sans-serif; }
.org-sub { font-size: 11px; color: #6b7280; margin-top: 3px; }
.rpt-title { text-align: right; font-size: 18px; font-weight: 700; color: #111827; }
.rpt-meta { text-align: right; font-size: 11px; color: #6b7280; margin-top: 3px; }

/* Summary bar */
.summary-bar { border: 1px solid #d1fae5; border-radius: 8px; margin-bottom: 22px; overflow: hidden; }
.summary-bar table { width: 100%; border-collapse: collapse; }
.summary-bar td { padding: 12px 18px; border-right: 1px solid #d1fae5; vertical-align: middle; }
.summary-bar td:last-child { border-right: none; }
.sum-label { font-size: 11px; color: #6b7280; }
.sum-val { font-size: 16px; font-weight: 700; margin-top: 2px; font-family: 'Noto Sans', Arial, sans-serif; }
.income { color: #059669; }
.expense { color: #dc2626; }
.net-val { color: #1d4ed8; }

/* Section title */
.sec-title {
    font-size: 12px; font-weight: 700; color: #065f46;
    background: #ecfdf5; padding: 8px 14px;
    border-left: 4px solid #059669; margin-bottom: 0;
    font-family: 'Noto Sans', Arial, sans-serif;
}

/* Tables */
table.data-tbl { width: 100%; border-collapse: collapse; margin-bottom: 22px; }
table.data-tbl thead th {
    background: #065f46; color: #fff;
    padding: 9px 12px; text-align: left;
    font-size: 11px; font-weight: 600;
    font-family: 'Noto Sans', Arial, sans-serif;
}
table.data-tbl thead th.r { text-align: right; }
table.data-tbl tbody td { padding: 8px 12px; border-bottom: 1px solid #f3f4f6; font-size: 12px; }
table.data-tbl tbody td.r { text-align: right; }
table.data-tbl tbody tr:nth-child(even) td { background: #f9fafb; }
table.data-tbl tfoot td {
    padding: 9px 12px; font-weight: 700;
    background: #f0fdf4; border-top: 2px solid #059669;
}
table.data-tbl tfoot td.r { text-align: right; }
.no-data { text-align: center; color: #9ca3af; padding: 24px; font-style: italic; }

/* Footer */
.rpt-footer { margin-top: 32px; text-align: center; color: #9ca3af; font-size: 10px; border-top: 1px solid #e5e7eb; padding-top: 12px; }

/* Print styles */
@media print {
    .no-print { display: none !important; }
    body { padding: 16px 20px; }
    @page { margin: 12mm 15mm; size: A4 landscape; }
}
</style>
</head>
<body>

<!-- Controls (hidden when printing) -->
<div class="no-print">
    <p>&#x1F4C4; Click <strong>Print / Save as PDF</strong> &rarr; select <strong>Save as PDF</strong> in the print dialog.</p>
    <div>
        <button class="btn-close" onclick="window.close()">&#x2715; Close</button>
        <button class="btn-print" onclick="window.print()">&#x1F5A8; Print / Save as PDF</button>
    </div>
</div>

<!-- Report Header -->
<div class="rpt-header">
    <table>
        <tr>
            <td>
                <div class="org-name">SUMITY</div>
                <div class="org-sub">Group Savings &amp; Expense Management System</div>
            </td>
            <td style="text-align:right;">
                <div class="rpt-title">Daily Financial Report</div>
                <div class="rpt-meta">Date: {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</div>
                <div class="rpt-meta">Generated: {{ now()->format('d M Y, H:i') }}</div>
            </td>
        </tr>
    </table>
</div>

<!-- Summary -->
<div class="summary-bar">
    <table>
        <tr style="background:#f0fdf4;">
            <td><div class="sum-label">Total Income</div><div class="sum-val income">BDT {{ number_format($totalIncome, 2) }}</div></td>
            <td><div class="sum-label">Total Expense</div><div class="sum-val expense">BDT {{ number_format($totalExpense, 2) }}</div></td>
            <td><div class="sum-label">Net</div><div class="sum-val net-val">BDT {{ number_format($net, 2) }}</div></td>
            <td><div class="sum-label">Deposits</div><div class="sum-val">{{ $deposits->count() }}</div></td>
            <td><div class="sum-label">Expenses</div><div class="sum-val">{{ $expenses->count() }}</div></td>
        </tr>
    </table>
</div>

<!-- Deposits -->
<div class="sec-title">Deposits ({{ $deposits->count() }})</div>
<table class="data-tbl">
    <thead>
        <tr>
            <th style="width:40px;">#</th>
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
            <td style="font-family:'Noto Sans',Arial,sans-serif;font-size:11px;color:#6b7280;">{{ $d->member->member_code ?? '—' }}</td>
            <td class="r income" style="font-weight:700;font-family:'Noto Sans',Arial,sans-serif;">{{ number_format($d->amount, 2) }}</td>
            <td>{{ ucfirst($d->payment_method) }}</td>
        </tr>
        @empty
        <tr><td colspan="5" class="no-data">No deposits recorded for this date</td></tr>
        @endforelse
    </tbody>
    @if($deposits->count())
    <tfoot>
        <tr>
            <td colspan="3" style="text-align:right;font-family:'Noto Sans',Arial,sans-serif;">Total Income:</td>
            <td class="r income" style="font-family:'Noto Sans',Arial,sans-serif;">{{ number_format($totalIncome, 2) }}</td>
            <td></td>
        </tr>
    </tfoot>
    @endif
</table>

<!-- Expenses -->
<div class="sec-title">Expenses ({{ $expenses->count() }})</div>
<table class="data-tbl">
    <thead>
        <tr>
            <th style="width:40px;">#</th>
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
            <td class="r expense" style="font-weight:700;font-family:'Noto Sans',Arial,sans-serif;">{{ number_format($e->amount, 2) }}</td>
            <td style="color:#9ca3af;font-size:11px;">{{ $e->notes ?? '—' }}</td>
        </tr>
        @empty
        <tr><td colspan="5" class="no-data">No expenses recorded for this date</td></tr>
        @endforelse
    </tbody>
    @if($expenses->count())
    <tfoot style="background:#fef2f2;">
        <tr>
            <td colspan="3" style="text-align:right;font-family:'Noto Sans',Arial,sans-serif;">Total Expense:</td>
            <td class="r expense" style="font-family:'Noto Sans',Arial,sans-serif;">{{ number_format($totalExpense, 2) }}</td>
            <td></td>
        </tr>
    </tfoot>
    @endif
</table>

<div class="rpt-footer">
    SUMITY &mdash; Confidential Financial Report &mdash; {{ now()->format('d M Y, H:i') }}
</div>

<script>
// Wait for Bengali font to load before triggering print
document.fonts.ready.then(function () {
    setTimeout(function () { window.print(); }, 600);
});
</script>
</body>
</html>
