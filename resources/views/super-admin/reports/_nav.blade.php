@php
    $tab = fn($active) => $active
        ? 'px-4 py-2 rounded-lg text-sm font-medium bg-indigo-600 text-white'
        : 'px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100';
@endphp
<div class="flex flex-wrap gap-2 mb-6">
    <a href="{{ route('super-admin.reports.index') }}" class="{{ $tab(request()->routeIs('super-admin.reports.index')) }}">Overview</a>
    <a href="{{ route('super-admin.reports.companies') }}" class="{{ $tab(request()->routeIs('super-admin.reports.companies')) }}">Companies</a>
    <a href="{{ route('super-admin.reports.payments') }}" class="{{ $tab(request()->routeIs('super-admin.reports.payments')) }}">Payment History</a>
    <a href="{{ route('super-admin.reports.daily') }}" class="{{ $tab(request()->routeIs('super-admin.reports.daily')) }}">Daily</a>
    <a href="{{ route('super-admin.reports.monthly') }}" class="{{ $tab(request()->routeIs('super-admin.reports.monthly')) }}">Monthly</a>
    <a href="{{ route('super-admin.reports.yearly') }}" class="{{ $tab(request()->routeIs('super-admin.reports.yearly')) }}">Yearly</a>
</div>
