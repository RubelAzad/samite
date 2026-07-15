@extends('layouts.super-admin')

@section('title', 'Packages')

@section('content')
<div class="mt-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Subscription Packages</h2>
            <p class="text-gray-500 text-sm">Pricing tiers offered to companies</p>
        </div>
        <a href="{{ route('super-admin.packages.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium transition text-sm">
            <i class="fas fa-plus"></i> Create Package
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Package</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Price</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Member Limit</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Features</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Companies</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Status</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($packages as $package)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $package->name }}</td>
                    <td class="px-6 py-4">{{ $package->price }}/{{ $package->billing_cycle }}</td>
                    <td class="px-6 py-4">{{ $package->member_limit }}</td>
                    <td class="px-6 py-4 text-gray-500 text-xs">
                        {{ collect($package->features)->map(fn($f) => \App\Http\Controllers\SuperAdmin\PackageController::FEATURES[$f] ?? $f)->join(', ') ?: '—' }}
                    </td>
                    <td class="px-6 py-4">{{ $package->companies_count }}</td>
                    <td class="px-6 py-4">
                        @if($package->isActive())
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">Active</span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('super-admin.packages.edit', $package) }}" class="text-gray-500 hover:text-indigo-600" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('super-admin.packages.toggle-status', $package) }}">
                                @csrf
                                <button type="submit" class="text-gray-500 hover:text-red-600" title="{{ $package->isActive() ? 'Deactivate' : 'Activate' }}">
                                    <i class="fas {{ $package->isActive() ? 'fa-ban' : 'fa-circle-check' }}"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-400">No packages yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection
