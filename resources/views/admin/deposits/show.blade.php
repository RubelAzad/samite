@extends('layouts.admin')

@section('title', 'Deposit Detail')

@section('content')
<div class="mt-6 max-w-xl">
    <div class="mb-6">
        <a href="{{ route('admin.deposits.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left mr-1"></i> Back to Deposits
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Deposit #{{ $deposit->id }}</h2>
                <p class="text-sm text-gray-400 mt-0.5">{{ $deposit->member->user->name ?? '' }} · {{ $deposit->member->member_code ?? '' }}</p>
            </div>
            <span class="text-sm px-3 py-1.5 rounded-full font-semibold
                {{ $deposit->status === 'approved' ? 'bg-green-100 text-green-700' : ($deposit->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                {{ ucfirst($deposit->status) }}
            </span>
        </div>

        {{-- Details --}}
        <dl class="divide-y divide-gray-100 text-sm">
            <div class="flex justify-between px-6 py-3.5">
                <dt class="text-gray-500">Amount</dt>
                <dd class="text-xl font-bold text-emerald-700">৳{{ number_format($deposit->amount, 2) }}</dd>
            </div>
            <div class="flex justify-between px-6 py-3.5">
                <dt class="text-gray-500">Date</dt>
                <dd class="font-medium text-gray-800">{{ $deposit->date->format('d F Y') }}</dd>
            </div>
            <div class="flex justify-between px-6 py-3.5">
                <dt class="text-gray-500">Payment Method</dt>
                <dd class="capitalize font-medium text-gray-800">{{ $deposit->payment_method }}</dd>
            </div>
            @if($deposit->notes)
            <div class="px-6 py-3.5">
                <dt class="text-gray-500 mb-1">Notes</dt>
                <dd class="text-gray-800">{{ $deposit->notes }}</dd>
            </div>
            @endif
            @if($deposit->rejection_reason)
            <div class="px-6 py-3.5">
                <dt class="text-gray-500 mb-1">Rejection Reason</dt>
                <dd class="text-red-700 font-medium">{{ $deposit->rejection_reason }}</dd>
            </div>
            @endif
            @if($deposit->approved_at)
            <div class="flex justify-between px-6 py-3.5">
                <dt class="text-gray-500">{{ $deposit->status === 'approved' ? 'Approved' : 'Processed' }} At</dt>
                <dd class="text-gray-800">{{ $deposit->approved_at->format('d M Y, H:i') }}</dd>
            </div>
            <div class="flex justify-between px-6 py-3.5">
                <dt class="text-gray-500">{{ $deposit->status === 'approved' ? 'Approved' : 'Processed' }} By</dt>
                <dd class="text-gray-800">{{ $deposit->approvedBy->name ?? 'N/A' }}</dd>
            </div>
            @endif
        </dl>

        {{-- Payment Proof / Attachment --}}
        @if($deposit->attachment)
        <div class="px-6 py-5 border-t border-gray-100">
            <p class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <i class="fas fa-image text-emerald-500"></i> Payment Proof
            </p>
            @php $ext = strtolower(pathinfo($deposit->attachment, PATHINFO_EXTENSION)); @endphp
            @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                <div x-data="{ zoom: false }">
                    <img src="{{ asset('storage/' . $deposit->attachment) }}"
                         alt="Payment Proof"
                         @click="zoom = true"
                         class="w-full max-h-80 object-contain rounded-xl border border-gray-200 bg-gray-50 cursor-zoom-in">
                    {{-- Lightbox --}}
                    <div x-show="zoom" x-cloak
                         class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4"
                         @click="zoom = false">
                        <img src="{{ asset('storage/' . $deposit->attachment) }}"
                             alt="Payment Proof"
                             class="max-w-full max-h-full rounded-xl shadow-2xl">
                        <button @click="zoom = false"
                                class="absolute top-4 right-4 w-10 h-10 bg-white/20 hover:bg-white/40 rounded-full flex items-center justify-center text-white transition-colors">
                            <i class="fas fa-xmark text-lg"></i>
                        </button>
                    </div>
                </div>
                <a href="{{ asset('storage/' . $deposit->attachment) }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 mt-3 text-xs text-emerald-600 hover:underline">
                    <i class="fas fa-external-link-alt"></i> Open full size
                </a>
            @else
                <a href="{{ asset('storage/' . $deposit->attachment) }}"
                   target="_blank"
                   class="flex items-center gap-3 border border-gray-200 rounded-xl px-4 py-3 hover:bg-gray-50 transition-colors">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-file-pdf text-red-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Payment Receipt (PDF)</p>
                        <p class="text-xs text-gray-400">Click to open</p>
                    </div>
                    <i class="fas fa-arrow-up-right-from-square text-gray-400 ml-auto"></i>
                </a>
            @endif
        </div>
        @else
        <div class="px-6 py-4 border-t border-gray-100">
            <p class="text-sm text-gray-400 flex items-center gap-2">
                <i class="fas fa-image text-gray-300"></i>
                No payment proof uploaded
            </p>
        </div>
        @endif

        {{-- Actions --}}
        @if($deposit->isPending())
        <div class="px-6 py-5 border-t border-gray-100 flex gap-3" x-data="{ open: false }">
            <form method="POST" action="{{ route('admin.deposits.approve', $deposit) }}" class="flex-1">
                @csrf
                <button type="submit"
                        class="w-full bg-emerald-600 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-emerald-700 transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-circle-check"></i> Approve Deposit
                </button>
            </form>
            <button @click="open = !open"
                    class="flex-1 bg-red-50 text-red-600 border border-red-200 py-2.5 rounded-xl text-sm font-semibold hover:bg-red-100 transition-colors flex items-center justify-center gap-2">
                <i class="fas fa-circle-xmark"></i> Reject
            </button>

            {{-- Reject modal --}}
            <div x-show="open" x-cloak
                 class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
                 @click.self="open = false">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
                    <h3 class="font-bold text-gray-800 mb-1">Reject Deposit</h3>
                    <p class="text-sm text-gray-500 mb-4">Please provide a reason so the member can understand.</p>
                    <form method="POST" action="{{ route('admin.deposits.reject', $deposit) }}">
                        @csrf
                        <textarea name="rejection_reason" required rows="3"
                                  placeholder="e.g. Amount doesn't match, invalid screenshot..."
                                  class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm mb-4 focus:outline-none focus:ring-2 focus:ring-red-500 resize-none"></textarea>
                        <div class="flex gap-3">
                            <button type="submit"
                                    class="flex-1 bg-red-600 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-red-700">
                                Confirm Reject
                            </button>
                            <button type="button" @click="open = false"
                                    class="flex-1 bg-gray-100 text-gray-700 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-200">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
