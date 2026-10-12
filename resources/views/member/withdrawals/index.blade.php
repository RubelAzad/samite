@extends('layouts.member')

@section('title', 'My Withdrawals')

@section('content')
<div class="py-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">My Withdrawals</h2>
            <p class="text-gray-500 text-sm">Track all your withdrawals</p>
        </div>
    </div>

    <!-- Filter -->
    <form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-6 flex flex-wrap gap-3">
        <select name="status" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
        <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded-lg text-sm">Filter</button>
        <a href="{{ route('member.withdrawals.index') }}" class="bg-gray-100 text-gray-600 px-5 py-2 rounded-lg text-sm">Reset</a>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Date</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Amount</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Method</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Purpose</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Notes</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Status</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Remark</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($withdrawals as $withdrawal)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-600">{{ $withdrawal->date->format('d M Y') }}</td>
                    <td class="px-6 py-4 font-bold text-gray-800">৳{{ number_format($withdrawal->amount, 0) }}</td>
                    <td class="px-6 py-4 capitalize text-gray-600">{{ $withdrawal->payment_method }}</td>
                    <td class="px-6 py-4 text-gray-500 max-w-xs truncate">{{ $withdrawal->purpose ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-500 max-w-xs truncate">{{ $withdrawal->notes ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <span class="text-xs px-2 py-1 rounded-full font-medium
                            {{ $withdrawal->status === 'approved' ? 'bg-green-100 text-green-700' : ($withdrawal->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($withdrawal->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-xs text-red-500">{{ $withdrawal->rejection_reason ?? '' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                        <i class="fas fa-hand-holding-usd text-3xl mb-2 block"></i>
                        No withdrawals found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $withdrawals->links() }}
        </div>
        </div>
    </div>
</div>
@endsection