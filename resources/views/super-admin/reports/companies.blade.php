@extends('layouts.super-admin')

@section('title', 'Company Report')

@section('content')
<div class="mt-6">
    @include('super-admin.reports._nav')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Company Status Report</h2>
        @include('super-admin.reports._export-buttons', ['route' => 'super-admin.reports.companies.export', 'params' => ['search' => $search, 'status' => $status]])
    </div>

    <form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-6 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search by company name..."
               class="flex-1 min-w-48 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <select name="status" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">All Status</option>
            <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
            <option value="expiring" {{ $status === 'expiring' ? 'selected' : '' }}>Expiring Soon</option>
            <option value="expired" {{ $status === 'expired' ? 'selected' : '' }}>Expired</option>
        </select>
        <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded-lg text-sm hover:bg-gray-700">
            <i class="fas fa-search mr-1"></i> Filter
        </button>
        <a href="{{ route('super-admin.reports.companies') }}" class="bg-gray-100 text-gray-600 px-5 py-2 rounded-lg text-sm hover:bg-gray-200">
            Reset
        </a>
    </form>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Company</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Package</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Expires</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Total Revenue</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($companies as $company)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $company->name }}</td>
                    <td class="px-6 py-4">{{ $company->subscriptionPackage->name ?? '—' }}</td>
                    <td class="px-6 py-4">{{ $company->subscription_ends_at?->format('d M Y') ?? '—' }}</td>
                    <td class="px-6 py-4">{{ number_format($company->subscriptions_sum_amount ?? 0, 2) }}</td>
                    <td class="px-6 py-4">@include('super-admin.partials.status-badge')</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-400">No companies match this filter.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection
