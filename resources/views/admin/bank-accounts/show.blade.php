@extends('layouts.admin')

@section('title', 'Bank Account Detail')

@section('content')
<div class="mt-6 max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('admin.bank-accounts.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left mr-1"></i> Back to Bank Accounts
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <div>
                <h2 class="text-xl font-bold text-gray-800">{{ $bankAccount->name }}</h2>
                <p class="text-sm text-gray-400 mt-0.5">{{ $bankAccount->bank_name }} · {{ $bankAccount->account_number }}</p>
            </div>
            <span class="text-sm px-3 py-1.5 rounded-full font-semibold
                {{ $bankAccount->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                {{ $bankAccount->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>

        <!-- Details -->
        <dl class="divide-y divide-gray-100 text-sm">
            <div class="flex justify-between px-6 py-3.5">
                <dt class="text-gray-500">Bank Name</dt>
                <dd class="font-medium text-gray-800">{{ $bankAccount->bank_name }}</dd>
            </div>
            <div class="flex justify-between px-6 py-3.5">
                <dt class="text-gray-500">Account Number</dt>
                <dd class="font-mono font-medium text-gray-800">{{ $bankAccount->account_number }}</dd>
            </div>
            <div class="flex justify-between px-6 py-3.5">
                <dt class="text-gray-500">Branch</dt>
                <dd class="font-medium text-gray-800">{{ $bankAccount->branch ?? '—' }}</dd>
            </div>
            <div class="flex justify-between px-6 py-3.5">
                <dt class="text-gray-500">Account Type</dt>
                <dd class="capitalize font-medium text-gray-800">{{ $bankAccount->type }}</dd>
            </div>
            <div class="flex justify-between px-6 py-3.5">
                <dt class="text-gray-500">Opening Balance</dt>
                <dd class="font-bold text-emerald-700">৳{{ number_format($bankAccount->opening_balance, 2) }}</dd>
            </div>
            <div class="flex justify-between px-6 py-3.5">
                <dt class="text-gray-500">Current Balance</dt>
                <dd class="font-bold text-gray-800 text-lg">৳{{ number_format($bankAccount->current_balance, 2) }}</dd>
            </div>
            @if($bankAccount->notes)
            <div class="px-6 py-3.5">
                <dt class="text-gray-500 mb-1">Notes</dt>
                <dd class="text-gray-800">{{ $bankAccount->notes }}</dd>
            </div>
            @endif
        </dl>

        <!-- Actions -->
        <div class="px-6 py-5 border-t border-gray-100 flex gap-3">
            <a href="{{ route('admin.bank-accounts.edit', $bankAccount) }}"
               class="flex-1 bg-indigo-50 text-indigo-600 border border-indigo-200 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-100 transition-colors flex items-center justify-center gap-2">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>

    <!-- Recent Transactions -->
    @if($bankAccount->withdrawals->count() > 0 || $bankAccount->ledgerEntries->count() > 0)
    <div class="mt-6 bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Recent Transactions</h3>
        </div>
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Date</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Type</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Reference</th>
                    <th class="px-6 py-3 text-right font-semibold text-gray-600">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($bankAccount->ledgerEntries->sortByDesc('date')->take(10) as $entry)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-600">{{ $entry->date->format('d M Y') }}</td>
                    <td class="px-6 py-4 capitalize">
                        <span class="text-xs px-2 py-1 rounded-full font-medium
                            {{ $entry->type === 'withdrawal' ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                            {{ $entry->type }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        @if($entry->reference_type === 'withdrawal' && $entry->member)
                            Withdrawal #{{ $entry->reference_id }} - {{ $entry->member->user->name }}
                        @elseif($entry->reference_type === 'deposit')
                            Deposit #{{ $entry->reference_id }}
                        @else
                            {{ ucfirst($entry->reference_type) }} #{{ $entry->reference_id }}
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right font-medium
                        {{ $entry->type === 'withdrawal' ? 'text-red-600' : 'text-emerald-700' }}">
                        {{ $entry->type === 'withdrawal' ? '–' : '+' }} ৳{{ number_format($entry->debit_amount + $entry->credit_amount, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
    @endif
</div>
@endsection