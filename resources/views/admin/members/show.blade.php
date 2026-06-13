@extends('layouts.admin')

@section('title', 'Member Profile')

@section('content')
<div class="mt-6">
    <div class="mb-6">
        <a href="{{ route('admin.members.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left mr-1"></i> Back to Members
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 text-center">
            <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-700 font-bold text-3xl mx-auto mb-4">
                {{ strtoupper(substr($member->user->name, 0, 1)) }}
            </div>
            <h2 class="text-xl font-bold text-gray-800">{{ $member->user->name }}</h2>
            <p class="text-gray-500 text-sm mb-1">{{ $member->user->email }}</p>
            <p class="font-mono text-emerald-700 font-semibold mb-3">{{ $member->member_code }}</p>
            <span class="text-xs px-3 py-1 rounded-full font-medium
                {{ $member->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ ucfirst($member->status) }}
            </span>

            <div class="mt-4 pt-4 border-t border-gray-100 text-left space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Phone</span>
                    <span class="text-gray-800">{{ $member->phone ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Join Date</span>
                    <span class="text-gray-800">{{ $member->join_date->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Plan</span>
                    <span class="text-gray-800 capitalize">{{ $member->deposit_plan }}</span>
                </div>
                @if($member->address)
                <div>
                    <span class="text-gray-500">Address</span>
                    <p class="text-gray-800 mt-1">{{ $member->address }}</p>
                </div>
                @endif
            </div>

            <div class="mt-4 flex gap-2">
                <a href="{{ route('admin.members.edit', $member) }}"
                   class="flex-1 bg-emerald-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 text-center">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <form method="POST" action="{{ route('admin.members.toggle-status', $member) }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full {{ $member->status === 'active' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }} py-2 rounded-lg text-sm font-medium">
                        {{ $member->status === 'active' ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
            </div>
            <div class="mt-2">
                <a href="{{ route('admin.users.password.edit', $member->user) }}"
                   class="w-full flex items-center justify-center gap-2 bg-blue-50 text-blue-700 hover:bg-blue-100 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-key"></i> Reset Password
                </a>
            </div>
        </div>

        <!-- Stats + Deposits -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Summary Cards -->
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white rounded-xl shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-emerald-700">৳{{ number_format($member->totalApprovedDeposits(), 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Total Deposited</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-yellow-600">{{ $member->deposits->where('status', 'pending')->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Pending</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-blue-700">{{ $member->deposits->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Total Deposits</p>
                </div>
            </div>

            <!-- Deposit History -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">Deposit History</h3>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600">Date</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600">Amount</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600">Method</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($member->deposits->sortByDesc('date') as $deposit)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">{{ $deposit->date->format('d M Y') }}</td>
                            <td class="px-6 py-3 font-semibold text-gray-800">৳{{ number_format($deposit->amount, 0) }}</td>
                            <td class="px-6 py-3 capitalize text-gray-600">{{ $deposit->payment_method }}</td>
                            <td class="px-6 py-3">
                                <span class="text-xs px-2 py-0.5 rounded-full
                                    {{ $deposit->status === 'approved' ? 'bg-green-100 text-green-700' : ($deposit->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ ucfirst($deposit->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">No deposits yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
