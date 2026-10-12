@extends('layouts.admin')

@section('title', 'Edit Deposit')

@section('content')
<div class="mt-6 max-w-xl">
    <div class="mb-6">
        <a href="{{ route('admin.deposits.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left mr-1"></i> Back to Deposits
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Edit Deposit #{{ $deposit->id }}</h2>

        <form method="POST" action="{{ route('admin.deposits.update', $deposit) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Member *</label>
                    <select name="member_id" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('member_id') border-red-400 @enderror">
                        <option value="">Select member...</option>
                        @foreach($members as $m)
                        <option value="{{ $m->id }}" {{ old('member_id', $deposit->member_id) == $m->id ? 'selected' : '' }}>
                            {{ $m->user->name }} ({{ $m->member_code }})
                        </option>
                        @endforeach
                    </select>
                    @error('member_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount (৳) *</label>
                    <input type="number" name="amount" value="{{ old('amount', $deposit->amount) }}" step="0.01" min="1" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                    <input type="date" name="date" value="{{ old('date', $deposit->date->toDateString()) }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method *</label>
                    <select name="payment_method" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="cash" {{ old('payment_method', $deposit->payment_method) === 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bkash" {{ old('payment_method', $deposit->payment_method) === 'bkash' ? 'selected' : '' }}>bKash</option>
                        <option value="nagad" {{ old('payment_method', $deposit->payment_method) === 'nagad' ? 'selected' : '' }}>Nagad</option>
                        <option value="bank" {{ old('payment_method', $deposit->payment_method) === 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                              placeholder="Optional notes...">{{ old('notes', $deposit->notes) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Payment Receipt / Screenshot
                        <span class="text-gray-400 font-normal text-xs ml-1">(optional)</span>
                    </label>
                    @if($deposit->attachment)
                        <div class="mb-2 flex items-center gap-3">
                            <a href="{{ asset('storage/' . $deposit->attachment) }}" target="_blank" class="text-emerald-600 hover:underline text-sm">
                                <i class="fas fa-file mr-1"></i> Current attachment
                            </a>
                            <label class="inline-flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                <input type="checkbox" name="remove_attachment" value="1" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                Remove attachment
                            </label>
                        </div>
                    @endif
                    <input type="file" name="attachment" accept="image/jpeg,image/png,image/gif,image/webp,application/pdf"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:text-emerald-700 file:font-medium hover:file:bg-emerald-100">
                    @error('attachment')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-6 p-4 bg-emerald-50 rounded-lg text-sm text-emerald-800">
                <i class="fas fa-info-circle mr-1"></i>
                If deposit is approved, ledger will be recalculated automatically.
            </div>

            <div class="mt-4 flex items-center gap-3">
                <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-lg font-medium transition text-sm">
                    <i class="fas fa-save mr-2"></i> Save Changes
                </button>
                <a href="{{ route('admin.deposits.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection