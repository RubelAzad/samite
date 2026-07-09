@extends('layouts.admin')

@section('title', 'Member-Wise Payment Report')

@section('content')
<div class="mt-6">
    <div class="flex items-center justify-between mb-6" x-data="{ exportOpen: false, exportMember: '{{ $selectedMember->id ?? '' }}', exportFrom: '{{ $from ?? '' }}', exportTo: '{{ $to ?? '' }}' }">
        <h2 class="text-2xl font-bold text-gray-800">Member-Wise Payment Report</h2>
        <button @click="exportOpen = true"
                class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">
            <i class="fas fa-download"></i> Download Report
        </button>

        <div x-show="exportOpen" x-cloak
             class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
             @click.self="exportOpen = false">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 overflow-hidden">
                <div class="bg-emerald-600 px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-file-download text-white text-lg"></i>
                        <h3 class="text-white font-bold text-base">Download Report</h3>
                    </div>
                    <button @click="exportOpen = false" class="text-white/70 hover:text-white">
                        <i class="fas fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">From Date</label>
                        <input type="date" x-model="exportFrom"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">To Date</label>
                        <input type="date" x-model="exportTo"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <a :href="'{{ route('admin.reports.member-wise.export') }}?format=excel&member_id=' + exportMember + '&from=' + exportFrom + '&to=' + exportTo"
                           class="flex flex-col items-center gap-2 bg-green-50 hover:bg-green-100 border border-green-200 text-green-800 py-4 rounded-xl transition text-sm font-semibold">
                            <i class="fas fa-file-excel text-green-600 text-2xl"></i>
                            Download Excel
                            <span class="text-xs font-normal text-green-600">.xlsx file</span>
                        </a>
                        <a :href="'{{ route('admin.reports.member-wise.print') }}?member_id=' + exportMember + '&from=' + exportFrom + '&to=' + exportTo"
                           target="_blank" @click="exportOpen = false"
                           class="flex flex-col items-center gap-2 bg-red-50 hover:bg-red-100 border border-red-200 text-red-800 py-4 rounded-xl transition text-sm font-semibold">
                            <i class="fas fa-file-pdf text-red-600 text-2xl"></i>
                            Download PDF
                            <span class="text-xs font-normal text-red-600">print &amp; save</span>
                        </a>
                    </div>
                    <p class="text-xs text-gray-400 text-center">PDF opens a print-ready page. Select "Save as PDF" in the print dialog.</p>
                </div>
            </div>
        </div>
    </div>

    <form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-6 flex items-center gap-3 flex-wrap">
        <label class="text-sm font-medium text-gray-700">Member:</label>
        <select name="member_id"
                class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 min-w-[200px]">
            <option value="">— All Members —</option>
            @foreach(\App\Models\Member::with('user')->orderBy('member_code')->get() as $m)
            <option value="{{ $m->id }}" {{ request('member_id') == $m->id ? 'selected' : '' }}>
                {{ $m->member_code }} – {{ $m->user->name }}
            </option>
            @endforeach
        </select>
        <label class="text-sm font-medium text-gray-700">From:</label>
        <input type="date" name="from" value="{{ $from }}"
               class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
        <label class="text-sm font-medium text-gray-700">To:</label>
        <input type="date" name="to" value="{{ $to }}"
               class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
        <button type="submit" class="bg-emerald-600 text-white px-5 py-2 rounded-lg text-sm">View Report</button>
        <a href="{{ route('admin.reports.member-wise') }}" class="bg-gray-100 text-gray-600 px-5 py-2 rounded-lg text-sm">Reset</a>
    </form>

    @if($selectedMember || $from || $to)
    <div class="bg-blue-50 border border-blue-200 text-blue-800 text-sm rounded-lg px-5 py-3 mb-6">
        <i class="fas fa-info-circle mr-1"></i>
        @if($selectedMember)
            Report for <strong>{{ $selectedMember->user->name }}</strong> ({{ $selectedMember->member_code }})
            @if($from && $to) from <strong>{{ $from }}</strong> to <strong>{{ $to }}</strong>
            @elseif($from) from <strong>{{ $from }}</strong>
            @elseif($to) up to <strong>{{ $to }}</strong>
            @endif
        @else
            Showing all members
            @if($from && $to) from <strong>{{ $from }}</strong> to <strong>{{ $to }}</strong>
            @elseif($from) from <strong>{{ $from }}</strong>
            @elseif($to) up to <strong>{{ $to }}</strong>
            @else — all time
            @endif
        @endif
        &mdash; <strong>{{ $grandTotal > 0 ? 'Total: ৳' . number_format($grandTotal, 2) : 'No data found' }}</strong>
    </div>
    @endif

    <div class="space-y-6">
        @forelse($members as $group)
        @php $m = $group->member; @endphp
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-emerald-50 to-green-50 border-b border-emerald-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-800 text-lg">{{ $m->user->name }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Code: {{ $m->member_code }} &middot;
                        Phone: {{ $m->phone ?? '—' }} &middot;
                        Total: <span class="font-semibold text-emerald-700">৳{{ number_format($group->total, 2) }}</span>
                    </p>
                </div>
                <span class="text-xs bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full font-medium">
                    {{ $group->deposits->count() }} payment{{ $group->deposits->count() !== 1 ? 's' : '' }}
                </span>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">#</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Date</th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-600">Amount</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Payment Method</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($group->deposits as $i => $d)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-gray-400 font-mono text-xs">{{ $i + 1 }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $d->date->format('d M Y') }}</td>
                        <td class="px-6 py-3 text-right font-semibold text-emerald-700">৳{{ number_format($d->amount, 2) }}</td>
                        <td class="px-6 py-3">
                            <span class="text-xs px-2 py-1 rounded-full font-medium
                                {{ ($d->payment_method ?? '') === 'cash' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ ($d->payment_method ?? '') === 'bkash' ? 'bg-pink-100 text-pink-700' : '' }}
                                {{ ($d->payment_method ?? '') === 'nagad' ? 'bg-orange-100 text-orange-700' : '' }}
                                {{ ($d->payment_method ?? '') === 'bank' ? 'bg-blue-100 text-blue-700' : '' }}">
                                {{ ucfirst($d->payment_method ?? '—') }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-gray-500 max-w-xs truncate">{{ $d->notes ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-emerald-50">
                    <tr>
                        <td colspan="2" class="px-6 py-3 text-right font-bold text-gray-700">Subtotal:</td>
                        <td class="px-6 py-3 text-right font-bold text-emerald-700">৳{{ number_format($group->total, 2) }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm p-12 text-center text-gray-400">
            <i class="fas fa-users text-4xl mb-3 block"></i>
            <p class="text-lg font-medium">No payment data found</p>
            <p class="text-sm mt-1">Select a member and adjust the date range.</p>
        </div>
        @endforelse
    </div>

    @if($grandTotal > 0 && $members->count() > 1)
    <div class="mt-6 bg-white rounded-xl shadow-sm p-5 border-l-4 border-emerald-500 flex items-center justify-between">
        <span class="text-lg font-bold text-gray-800">Grand Total</span>
        <span class="text-2xl font-bold text-emerald-700">৳{{ number_format($grandTotal, 2) }}</span>
    </div>
    @endif
</div>
@endsection
