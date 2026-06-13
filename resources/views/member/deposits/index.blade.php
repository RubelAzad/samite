@extends('layouts.member')

@section('title', 'My Deposits')

@section('content')
<div class="py-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">My Deposits</h2>
            <p class="text-gray-500 text-sm">Track all your deposit requests</p>
        </div>
        <a href="{{ route('member.deposits.create') }}"
           class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg font-medium transition text-sm">
            <i class="fas fa-plus-circle"></i> Submit Deposit
        </a>
    </div>

    <!-- Filter -->
    <form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-6 flex flex-wrap gap-3">
        <select name="status" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
        <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded-lg text-sm">Filter</button>
        <a href="{{ route('member.deposits.index') }}" class="bg-gray-100 text-gray-600 px-5 py-2 rounded-lg text-sm">Reset</a>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Date</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Amount</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Method</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Notes</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Status</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Remark</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($deposits as $deposit)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-600">{{ $deposit->date->format('d M Y') }}</td>
                    <td class="px-6 py-4 font-bold text-gray-800">৳{{ number_format($deposit->amount, 0) }}</td>
                    <td class="px-6 py-4 capitalize text-gray-600">{{ $deposit->payment_method }}</td>
                    <td class="px-6 py-4 text-gray-500 max-w-xs truncate">{{ $deposit->notes ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <span class="text-xs px-2 py-1 rounded-full font-medium
                            {{ $deposit->status === 'approved' ? 'bg-green-100 text-green-700' : ($deposit->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($deposit->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-xs text-red-500">{{ $deposit->rejection_reason ?? '' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        <i class="fas fa-inbox text-3xl mb-2 block"></i>
                        No deposits found.
                        <a href="{{ route('member.deposits.create') }}" class="text-emerald-600 hover:underline ml-1">Submit your first →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $deposits->links() }}
        </div>
    </div>
</div>
@endsection
