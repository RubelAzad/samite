@extends('layouts.admin')

@section('title', 'Ledger')

@section('content')
<div class="mt-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Unified Ledger</h2>
        <p class="text-gray-500 text-sm">Complete financial transaction history</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500 mb-1">Total Income</p>
            <p class="text-2xl font-bold text-emerald-700">৳{{ number_format($totalCredit, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500 mb-1">Total Expenses</p>
            <p class="text-2xl font-bold text-red-600">৳{{ number_format($totalDebit, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500 mb-1">Current Balance</p>
            <p class="text-2xl font-bold text-purple-700">৳{{ number_format($balance, 2) }}</p>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-6 flex flex-wrap gap-3">
        <select name="type" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="">All Types</option>
            <option value="deposit" {{ request('type') === 'deposit' ? 'selected' : '' }}>Deposits</option>
            <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Expenses</option>
            <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>Adjustments</option>
        </select>
        <input type="date" name="from" value="{{ request('from') }}" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
        <input type="date" name="to" value="{{ request('to') }}" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
        <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded-lg text-sm">Filter</button>
        <a href="{{ route('admin.ledger.index') }}" class="bg-gray-100 text-gray-600 px-5 py-2 rounded-lg text-sm">Reset</a>
    </form>

    <!-- Ledger Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">ID</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Date</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Type</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Member</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Description</th>
                    <th class="px-6 py-3 text-right font-semibold text-gray-600">Credit</th>
                    <th class="px-6 py-3 text-right font-semibold text-gray-600">Debit</th>
                    <th class="px-6 py-3 text-right font-semibold text-gray-600">Balance</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($entries as $entry)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-mono text-xs text-gray-400">#{{ $entry->id }}</td>
                    <td class="px-6 py-3 text-gray-600">{{ $entry->date->format('d M Y') }}</td>
                    <td class="px-6 py-3">
                        <span class="text-xs px-2 py-1 rounded-full font-medium
                            {{ $entry->type === 'deposit' ? 'bg-emerald-100 text-emerald-700' : ($entry->type === 'expense' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                            {{ ucfirst($entry->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-600">{{ $entry->member->user->name ?? '—' }}</td>
                    <td class="px-6 py-3 text-gray-600 max-w-xs truncate">{{ $entry->description }}</td>
                    <td class="px-6 py-3 text-right font-semibold text-emerald-700">
                        {{ $entry->credit_amount > 0 ? '৳' . number_format($entry->credit_amount, 2) : '—' }}
                    </td>
                    <td class="px-6 py-3 text-right font-semibold text-red-600">
                        {{ $entry->debit_amount > 0 ? '৳' . number_format($entry->debit_amount, 2) : '—' }}
                    </td>
                    <td class="px-6 py-3 text-right font-bold text-gray-800">৳{{ number_format($entry->balance_after, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                        <i class="fas fa-book text-3xl mb-2 block"></i>
                        No ledger entries found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $entries->links() }}
        </div>
    </div>
</div>
@endsection
