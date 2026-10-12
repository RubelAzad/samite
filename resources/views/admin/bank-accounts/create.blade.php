@extends('layouts.admin')

@section('title', 'Add Bank Account')

@section('content')
<div class="mt-6 max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.bank-accounts.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left mr-1"></i> Back to Bank Accounts
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Add New Bank Account</h2>

        <form method="POST" action="{{ route('admin.bank-accounts.store') }}">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Account Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                           placeholder="e.g., Main Operating Account">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bank Name *</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name') }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                           placeholder="e.g., Sonali Bank, BRAC Bank">
                    @error('bank_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Account Number *</label>
                    <input type="text" name="account_number" value="{{ old('account_number') }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                           placeholder="e.g., 0123456789012">
                    @error('account_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
                    <input type="text" name="branch" value="{{ old('branch') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                           placeholder="e.g., Dhaka Main Branch">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Account Type *</label>
                    <select name="type" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">Select type...</option>
                        <option value="savings" {{ old('type') === 'savings' ? 'selected' : '' }}>Savings</option>
                        <option value="current" {{ old('type') === 'current' ? 'selected' : '' }}>Current</option>
                        <option value="fd" {{ old('type') === 'fd' ? 'selected' : '' }}>Fixed Deposit</option>
                    </select>
                    @error('type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Opening Balance (৳)</label>
                    <input type="number" name="opening_balance" value="{{ old('opening_balance', 0) }}" step="0.01" min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('opening_balance')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="2" value="{{ old('notes') }}"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                              placeholder="Optional notes..."></textarea>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-3">
                <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-lg font-medium transition text-sm">
                    <i class="fas fa-save mr-2"></i> Save Bank Account
                </button>
                <a href="{{ route('admin.bank-accounts.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection