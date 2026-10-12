@extends('layouts.admin')

@section('title', 'Withdrawals')

@section('content')
<div class="mt-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Withdrawals</h2>
            <p class="text-gray-500 text-sm">Manage and approve member withdrawal requests</p>
        </div>
        <a href="{{ route('admin.withdrawals.create') }}"
           class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg font-medium transition text-sm">
            <i class="fas fa-minus-circle"></i> Record Withdrawal
        </a>
    </div>

    <!-- Filters -->
    <form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-6 flex flex-wrap gap-3">
        <select name="status" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
        <select name="purpose" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            <option value="">All Purposes</option>
            <option value="land_purchase" {{ request('purpose') === 'land_purchase' ? 'selected' : '' }}>Land Purchase</option>
            <option value="medical" {{ request('purpose') === 'medical' ? 'selected' : '' }}>Medical</option>
            <option value="business" {{ request('purpose') === 'business' ? 'selected' : '' }}>Business</option>
            <option value="education" {{ request('purpose') === 'education' ? 'selected' : '' }}>Education</option>
            <option value="emergency" {{ request('purpose') === 'emergency' ? 'selected' : '' }}>Emergency</option>
            <option value="other" {{ request('purpose') === 'other' ? 'selected' : '' }}>Other</option>
        </select>
        <select name="member_id" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            <option value="">All Members</option>
            @foreach($members as $m)
            <option value="{{ $m->id }}" {{ request('member_id') == $m->id ? 'selected' : '' }}>{{ $m->user->name }}</option>
            @endforeach
        </select>
        <select name="bank_account_id" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            <option value="">All Bank Accounts</option>
            @foreach($bankAccounts as $ba)
            <option value="{{ $ba->id }}" {{ request('bank_account_id') == $ba->id ? 'selected' : '' }}>{{ $ba->name }}</option>
            @endforeach
        </select>
        <input type="date" name="from" value="{{ request('from') }}" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
        <input type="date" name="to" value="{{ request('to') }}" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
        <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded-lg text-sm">
            <i class="fas fa-search mr-1"></i> Filter
        </button>
        <a href="{{ route('admin.withdrawals.index') }}" class="bg-gray-100 text-gray-600 px-5 py-2 rounded-lg text-sm">Reset</a>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">#</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Member</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Amount</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Date</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Method</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Bank Account</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Purpose</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Status</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($withdrawals as $withdrawal)
                <tr class="hover:bg-gray-50 {{ $withdrawal->isPending() ? 'bg-yellow-50/40' : '' }}">
                    <td class="px-6 py-4 text-gray-400 font-mono text-xs">#{{ $withdrawal->id }}</td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-800">{{ $withdrawal->member->user->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-400">{{ $withdrawal->member->member_code ?? '' }}</p>
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-800">৳{{ number_format($withdrawal->amount, 0) }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $withdrawal->date->format('d M Y') }}</td>
                    <td class="px-6 py-4 capitalize text-gray-600">{{ $withdrawal->payment_method }}</td>
                    <td class="px-6 py-4 text-gray-600">
                        @if($withdrawal->bankAccount)
                            {{ $withdrawal->bankAccount->name }}
                        @else
                            <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($withdrawal->purpose)
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                            {{ $withdrawal->purpose === 'land_purchase' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $withdrawal->purpose === 'medical' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $withdrawal->purpose === 'business' ? 'bg-purple-100 text-purple-700' : '' }}
                            {{ $withdrawal->purpose === 'education' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $withdrawal->purpose === 'emergency' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $withdrawal->purpose === 'other' ? 'bg-gray-100 text-gray-700' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $withdrawal->purpose)) }}
                        </span>
                        @else
                        <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs px-2 py-1 rounded-full font-medium
                            {{ $withdrawal->status === 'approved' ? 'bg-green-100 text-green-700' : ($withdrawal->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($withdrawal->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4" x-data="{ open: false }">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.withdrawals.show', $withdrawal) }}" class="text-blue-600 hover:text-blue-800 p-1" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($withdrawal->isPending())
                            <form method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-800 p-1" title="Approve" onclick="return confirm('Approve this withdrawal?')">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                            </form>
                            <button @click="open = !open" class="text-red-500 hover:text-red-700 p-1" title="Reject">
                                <i class="fas fa-times-circle"></i>
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
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                        <i class="fas fa-hand-holding-usd text-3xl mb-2 block"></i>
                        No withdrawals found
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