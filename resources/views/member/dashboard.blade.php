@extends('layouts.member')

@section('title', 'My Dashboard')

@section('content')
<div class="py-6">
    <!-- Welcome -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl p-6 text-white mb-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-emerald-100 text-sm">Welcome back,</p>
                <h1 class="text-2xl font-bold">{{ auth()->user()->name }}</h1>
                <p class="text-emerald-200 text-sm mt-1">{{ $member->member_code }} · Joined {{ $member->join_date->format('d M Y') }}</p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center text-3xl font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-emerald-600"></i>
                </div>
                <p class="text-sm text-gray-500">Total Deposited</p>
            </div>
            <p class="text-2xl font-bold text-gray-800">৳{{ number_format($totalApproved, 0) }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <p class="text-sm text-gray-500">Pending Requests</p>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $totalPending }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-check text-blue-600"></i>
                </div>
                <p class="text-sm text-gray-500">Deposit Plan</p>
            </div>
            <p class="text-2xl font-bold text-gray-800 capitalize">{{ $member->deposit_plan }}</p>
        </div>
    </div>

    <!-- Quick Action -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h2 class="font-semibold text-gray-800 mb-4">Quick Actions</h2>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('member.deposits.create') }}"
               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg font-medium transition text-sm">
                <i class="fas fa-plus-circle"></i> Submit Deposit
            </a>
            <a href="{{ route('member.deposits.index') }}"
               class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg font-medium transition text-sm">
                <i class="fas fa-list"></i> View All Deposits
            </a>
            <a href="{{ route('member.ledger.index') }}"
               class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg font-medium transition text-sm">
                <i class="fas fa-book"></i> My Ledger
            </a>
        </div>
    </div>

    <!-- Recent Deposits -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Recent Deposits</h2>
            <a href="{{ route('member.deposits.index') }}" class="text-sm text-emerald-600 hover:underline">View all</a>
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
                @forelse($recentDeposits as $deposit)
                <tr>
                    <td class="px-6 py-3 text-gray-600">{{ $deposit->date->format('d M Y') }}</td>
                    <td class="px-6 py-3 font-semibold text-gray-800">৳{{ number_format($deposit->amount, 0) }}</td>
                    <td class="px-6 py-3 capitalize text-gray-600">{{ $deposit->payment_method }}</td>
                    <td class="px-6 py-3">
                        <span class="text-xs px-2 py-1 rounded-full font-medium
                            {{ $deposit->status === 'approved' ? 'bg-green-100 text-green-700' : ($deposit->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($deposit->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                        No deposits yet. <a href="{{ route('member.deposits.create') }}" class="text-emerald-600 hover:underline">Make your first deposit →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
