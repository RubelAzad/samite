@extends('layouts.super-admin')

@section('title', 'Companies')

@section('content')
<div class="mt-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Companies</h2>
            <p class="text-gray-500 text-sm">All tenants running on this platform</p>
        </div>
        <a href="{{ route('super-admin.companies.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium transition text-sm">
            <i class="fas fa-plus"></i> Create Company
        </a>
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
        <a href="{{ route('super-admin.companies.index') }}" class="bg-gray-100 text-gray-600 px-5 py-2 rounded-lg text-sm hover:bg-gray-200">
            Reset
        </a>
    </form>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Company</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Users</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Members</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Package</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Expires</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Status</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($companies as $company)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-800">{{ $company->name }}</p>
                        <p class="text-gray-400 text-xs">{{ $company->slug }}</p>
                    </td>
                    <td class="px-6 py-4">{{ $company->users_count }}</td>
                    <td class="px-6 py-4">{{ $company->members_count }}</td>
                    <td class="px-6 py-4">{{ $company->subscriptionPackage->name ?? '—' }}</td>
                    <td class="px-6 py-4">
                        @if($company->subscription_ends_at)
                            <span class="{{ $company->isSubscriptionExpired() ? 'text-red-600' : ($company->isSubscriptionExpiringSoon() ? 'text-amber-600' : 'text-gray-600') }}">
                                {{ $company->subscription_ends_at->format('d M Y') }}
                            </span>
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-6 py-4">@include('super-admin.partials.status-badge')</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('super-admin.companies.edit', $company) }}" class="text-gray-500 hover:text-indigo-600" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('super-admin.companies.toggle-status', $company) }}">
                                @csrf
                                <button type="submit" class="text-gray-500 hover:text-red-600" title="{{ $company->isActive() ? 'Deactivate' : 'Activate' }}">
                                    <i class="fas {{ $company->isActive() ? 'fa-ban' : 'fa-circle-check' }}"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-400">No companies yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $companies->links() }}
    </div>
</div>
@endsection
