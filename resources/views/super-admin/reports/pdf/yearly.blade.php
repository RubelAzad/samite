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
            <div class="report-title">Yearly Revenue Report</div>
            <div class="report-meta">Year: {{ $year }}</div>
            <div class="report-meta">Generated: {{ now()->format('d M Y, H:i') }}</div>
        </td>
    </tr>
</table>

<table class="data">
    <thead>
        <tr>
            <th>Month</th>
            <th class="r">Revenue</th>
        </tr>
    </thead>
    <tbody>
        @foreach($monthlyTotals as $row)
        <tr>
            <td>{{ $row['month'] }} {{ $year }}</td>
            <td class="r">{{ number_format($row['total'], 2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td style="text-align:right;">Total:</td>
            <td class="r">{{ number_format($total, 2) }}</td>
        </tr>
    </tfoot>
</table>

<div class="footer">
    Samite Platform &mdash; Confidential Report &mdash; {{ now()->format('d M Y, H:i') }}
</div>
</body>
</html>
