@extends('layouts.admin')

@section('title', 'Record Withdrawal')

@section('content')
<div class="mt-6 max-w-xl">
    <div class="mb-6">
        <a href="{{ route('admin.withdrawals.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left mr-1"></i> Back to Withdrawals
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Record New Withdrawal</h2>

        <form method="POST" action="{{ route('admin.withdrawals.store') }}">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Member *</label>
                    <select name="member_id" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 @error('member_id') border-red-400 @enderror">
                        <option value="">Select member...</option>
                        @foreach($members as $m)
                        <option value="{{ $m->id }}" {{ old('member_id') == $m->id ? 'selected' : '' }}>
                            {{ $m->user->name }} ({{ $m->member_code }})
                        </option>
                        @endforeach
                    </select>
                    @error('member_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount (৳) *</label>
                    <input type="number" name="amount" value="{{ old('amount') }}" step="0.01" min="1" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    @error('amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                    <input type="date" name="date" value="{{ old('date', today()->toDateString()) }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method *</label>
                    <select name="payment_method" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="cash">Cash</option>
                        <option value="bkash">bKash</option>
                        <option value="nagad">Nagad</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bank Account</label>
                    <select name="bank_account_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="">Select bank account (optional)...</option>
                        @foreach($bankAccounts as $ba)
                        <option value="{{ $ba->id }}" {{ old('bank_account_id') == $ba->id ? 'selected' : '' }}>
                            {{ $ba->name }} ({{ $ba->bank_name }} - {{ $ba->account_number }})
                        </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Required for bank transfers to track which account was debited</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
                    <select name="purpose"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="">Select purpose...</option>
                        <option value="land_purchase" {{ old('purpose') === 'land_purchase' ? 'selected' : '' }}>Land Purchase</option>
                        <option value="medical" {{ old('purpose') === 'medical' ? 'selected' : '' }}>Medical Emergency</option>
                        <option value="business" {{ old('purpose') === 'business' ? 'selected' : '' }}>Business Investment</option>
                        <option value="education" {{ old('purpose') === 'education' ? 'selected' : '' }}>Education</option>
                        <option value="emergency" {{ old('purpose') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                        <option value="other" {{ old('purpose') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Purpose Details</label>
                    <textarea name="purpose_details" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                              placeholder="Additional details (e.g., plot number, hospital name, etc.)">{{ old('purpose_details') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                              placeholder="Optional notes...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-6 p-4 bg-red-50 rounded-lg text-sm text-red-800">
                <i class="fas fa-info-circle mr-1"></i>
                Admin-recorded withdrawals are auto-approved and immediately reflected in the ledger.
            </div>

            <div class="mt-4 flex items-center gap-3">
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-medium transition text-sm">
                    <i class="fas fa-save mr-2"></i> Record & Approve
                </button>
                <a href="{{ route('admin.withdrawals.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection