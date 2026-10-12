@extends('layouts.admin')

@section('title', 'Withdrawal #{{ $withdrawal->id }}')

@section('content')
<div class="mt-6 max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('admin.withdrawals.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left mr-1"></i> Back to Withdrawals
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-5 bg-gradient-to-r from-red-600 to-red-700">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white">Withdrawal #{{ $withdrawal->id }}</h2>
                    <p class="text-red-100 text-sm">{{ $withdrawal->member->user->name ?? 'N/A' }} ({{ $withdrawal->member->member_code }})</p>
                </div>
                <span class="text-xs px-3 py-1 rounded-full font-medium
                    {{ $withdrawal->status === 'approved' ? 'bg-green-100 text-green-800' : ($withdrawal->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                    {{ ucfirst($withdrawal->status) }}
                </span>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Amount</p>
                    <p class="text-2xl font-bold text-gray-800">৳{{ number_format($withdrawal->amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Date</p>
                    <p class="text-gray-800">{{ $withdrawal->date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Payment Method</p>
                    <p class="text-gray-800 capitalize">{{ $withdrawal->payment_method }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Bank Account</p>
                    <p class="text-gray-800">{{ $withdrawal->bankAccount->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Purpose</p>
                    <p class="text-gray-800 capitalize">{{ str_replace('_', ' ', $withdrawal->purpose ?? '—') }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Purpose Details</p>
                    <p class="text-gray-800">{{ $withdrawal->purpose_details ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</p>
                    <p class="text-gray-800 capitalize">{{ $withdrawal->status }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Notes</p>
                    <p class="text-gray-800">{{ $withdrawal->notes ?? '—' }}</p>
                </div>
            </div>

            <!-- Approval Info -->
            @if($withdrawal->approved_by)
            <div class="border-t border-gray-100 pt-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Approval Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Approved By</p>
                        <p class="font-medium">{{ $withdrawal->approvedBy->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Approved At</p>
                        <p class="font-medium">{{ $withdrawal->approved_at?->format('d M Y, h:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Rejection Reason</p>
                        <p class="font-medium text-red-600">{{ $withdrawal->rejection_reason ?? '—' }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions for Pending -->
            @if($withdrawal->isPending())
            <div class="border-t border-gray-100 pt-4 flex gap-3">
                <form method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal) }}" class="inline" onsubmit="return confirm('Approve this withdrawal?')">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg font-medium transition text-sm">
                        <i class="fas fa-check-circle mr-2"></i> Approve
                    </button>
                </form>
                <button @click="open = true" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-medium transition text-sm" x-data="{ open: false }">
                    <i class="fas fa-times-circle mr-2"></i> Reject
                </button>

                <!-- Reject Modal -->
                <div x-show="open" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click.self="open = false">
                    <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
                        <h3 class="font-semibold text-gray-800 mb-3">Reject Withdrawal</h3>
                        <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal) }}">
                            @csrf
                            <textarea name="rejection_reason" required placeholder="Reason for rejection..." rows="3"
                                      class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                            <div class="flex gap-2">
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm">Reject</button>
                                <button type="button" @click="open = false" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection