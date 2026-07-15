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
            <div class="report-title">Company Status Report</div>
            <div class="report-meta">Generated: {{ now()->format('d M Y, H:i') }}</div>
        </td>
    </tr>
</table>

<table class="data">
    <thead>
        <tr>
            <th>#</th>
            <th>Company</th>
            <th>Package</th>
            <th>Expires</th>
            <th class="r">Total Revenue</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($companies as $i => $company)
        @php $status = $company->subscriptionStatus(); @endphp
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $company->name }}</td>
            <td>{{ $company->subscriptionPackage->name ?? '—' }}</td>
            <td>{{ $company->subscription_ends_at?->format('d M Y') ?? '—' }}</td>
            <td class="r">{{ number_format($company->subscriptions_sum_amount ?? 0, 2) }}</td>
            <td><span class="badge badge-{{ $status }}">{{ ucfirst($status) }}</span></td>
        </tr>
        @empty
        <tr><td colspan="6" class="no-data">No companies match this filter</td></tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    Samite Platform &mdash; Confidential Report &mdash; {{ now()->format('d M Y, H:i') }}
</div>
</body>
</html>
