@extends('layouts.super-admin')

@section('title', 'Reports')

@section('content')
<div class="mt-6 space-y-6">
    @include('super-admin.reports._nav')

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-gray-500 text-sm">Total Revenue Collected</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalRevenue, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-gray-500 text-sm">Expiring in 7 Days</p>
            <p class="text-2xl font-bold text-amber-600 mt-1">{{ $expiringSoon->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-gray-500 text-sm">Currently Expired</p>
            <p class="text-2xl font-bold text-red-600 mt-1">{{ $expired->count() }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Revenue by Month</h3>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500">
                    <th class="py-1.5 pr-4">Month</th>
                    <th class="py-1.5 pr-4">Revenue</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($revenueByMonth as $row)
                <tr>
                    <td class="py-1.5 pr-4">{{ $row->month }}</td>
                    <td class="py-1.5 pr-4">{{ number_format($row->total, 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="2" class="py-4 text-center text-gray-400">No subscription payments recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Companies Expiring Soon</h3>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500">
                    <th class="py-1.5 pr-4">Company</th>
                    <th class="py-1.5 pr-4">Package</th>
                    <th class="py-1.5 pr-4">Expires</th>
                    <th class="py-1.5 pr-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($expiringSoon as $company)
                <tr>
                    <td class="py-1.5 pr-4">{{ $company->name }}</td>
                    <td class="py-1.5 pr-4">{{ $company->subscriptionPackage->name ?? '—' }}</td>
                    <td class="py-1.5 pr-4 text-amber-600">{{ $company->subscription_ends_at->format('d M Y') }}</td>
                    <td class="py-1.5 pr-4">@include('super-admin.partials.status-badge')</td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-4 text-center text-gray-400">Nothing expiring soon.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Expired Companies</h3>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500">
                    <th class="py-1.5 pr-4">Company</th>
                    <th class="py-1.5 pr-4">Package</th>
                    <th class="py-1.5 pr-4">Expired On</th>
                    <th class="py-1.5 pr-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($expired as $company)
                <tr>
                    <td class="py-1.5 pr-4">{{ $company->name }}</td>
                    <td class="py-1.5 pr-4">{{ $company->subscriptionPackage->name ?? '—' }}</td>
                    <td class="py-1.5 pr-4 text-red-600">{{ $company->subscription_ends_at->format('d M Y') }}</td>
                    <td class="py-1.5 pr-4">@include('super-admin.partials.status-badge')</td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-4 text-center text-gray-400">No expired companies.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
