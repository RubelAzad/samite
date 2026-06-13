@extends('layouts.member')

@section('title', 'My Ledger')

@section('content')
<div class="py-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">My Ledger</h2>
        <p class="text-gray-500 text-sm">Your personal financial transaction history</p>
    </div>

    <!-- Summary -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl p-6 text-white mb-6">
        <p class="text-emerald-100 text-sm">Total Contributed</p>
        <p class="text-4xl font-bold mt-1">৳{{ number_format($totalDeposited, 2) }}</p>
        <p class="text-emerald-200 text-sm mt-2">Member: {{ $member->member_code }}</p>
    </div>

    <!-- Ledger Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Date</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Description</th>
                    <th class="px-6 py-3 text-right font-semibold text-gray-600">Credit</th>
                    <th class="px-6 py-3 text-right font-semibold text-gray-600">Debit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($entries as $entry)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-600">{{ $entry->date->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $entry->description }}</td>
                    <td class="px-6 py-4 text-right font-semibold text-emerald-700">
                        {{ $entry->credit_amount > 0 ? '৳' . number_format($entry->credit_amount, 2) : '—' }}
                    </td>
                    <td class="px-6 py-4 text-right font-semibold text-red-600">
                        {{ $entry->debit_amount > 0 ? '৳' . number_format($entry->debit_amount, 2) : '—' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                        <i class="fas fa-book text-3xl mb-2 block"></i>
                        No transactions yet. Submit a deposit to get started.
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
