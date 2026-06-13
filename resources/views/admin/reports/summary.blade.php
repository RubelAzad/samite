@extends('layouts.admin')

@section('title', 'Full Summary Report')

@section('content')
<div class="mt-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6" x-data="{ exportOpen: false }">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Full Summary Report</h2>
            <p class="text-sm text-gray-500 mt-0.5">
                All data from {{ $startDate->format('M Y') }} to {{ $endDate->format('M Y') }}
            </p>
        </div>
        <button @click="exportOpen = true"
                class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">
            <i class="fas fa-download"></i> Download All Data
        </button>

        {{-- Export Modal --}}
        <div x-show="exportOpen" x-cloak
             class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
             @click.self="exportOpen = false">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 overflow-hidden">
                <div class="bg-emerald-600 px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-file-download text-white text-lg"></i>
                        <div>
                            <h3 class="text-white font-bold text-base">Download Full Report</h3>
                            <p class="text-emerald-200 text-xs">{{ $startDate->format('M Y') }} – {{ $endDate->format('M Y') }}</p>
                        </div>
                    </div>
                    <button @click="exportOpen = false" class="text-white/70 hover:text-white">
                        <i class="fas fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <p class="text-sm text-gray-600">This report contains <strong>all data</strong> — all members, all months, all deposits and expenses from the beginning.</p>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('admin.reports.summary.export') }}"
                           class="flex flex-col items-center gap-2 bg-green-50 hover:bg-green-100 border border-green-200 text-green-800 py-4 rounded-xl transition text-sm font-semibold">
                            <i class="fas fa-file-excel text-green-600 text-2xl"></i>
                            Download Excel
                            <span class="text-xs font-normal text-green-600">.xlsx file</span>
                        </a>
                        <a href="{{ route('admin.reports.summary.print') }}"
                           target="_blank" @click="exportOpen = false"
                           class="flex flex-col items-center gap-2 bg-red-50 hover:bg-red-100 border border-red-200 text-red-800 py-4 rounded-xl transition text-sm font-semibold">
                            <i class="fas fa-file-pdf text-red-600 text-2xl"></i>
                            Download PDF
                            <span class="text-xs font-normal text-red-600">print &amp; save</span>
                        </a>
                    </div>
                    <p class="text-xs text-gray-400 text-center">PDF opens a print-ready page — select "Save as PDF" in the print dialog.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-emerald-500">
            <p class="text-xs text-gray-500">Total Collected</p>
            <p class="text-xl font-bold text-emerald-700">৳{{ number_format($totalIncome, 0) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">{{ count($months) }} months</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-red-500">
            <p class="text-xs text-gray-500">Total Expenses</p>
            <p class="text-xl font-bold text-red-600">৳{{ number_format($totalExpense, 0) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-500">
            <p class="text-xs text-gray-500">Net Savings</p>
            <p class="text-xl font-bold text-blue-700">৳{{ number_format($totalIncome - $totalExpense, 0) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-purple-500">
            <p class="text-xs text-gray-500">Fund Balance</p>
            <p class="text-xl font-bold text-purple-700">৳{{ number_format($balance, 0) }}</p>
        </div>
    </div>

    {{-- Monthly Breakdown --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Monthly Breakdown</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Month</th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-600">Income (৳)</th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-600">Expense (৳)</th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-600">Net (৳)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($months as $label)
                    @php
                        $inc = $monthlyTotals[$label]['income']  ?? 0;
                        $exp = $monthlyTotals[$label]['expense'] ?? 0;
                        $net = $inc - $exp;
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium text-gray-700">{{ $label }}</td>
                        <td class="px-6 py-3 text-right font-semibold text-emerald-700">{{ $inc > 0 ? number_format($inc, 0) : '—' }}</td>
                        <td class="px-6 py-3 text-right font-semibold text-red-600">{{ $exp > 0 ? number_format($exp, 0) : '—' }}</td>
                        <td class="px-6 py-3 text-right font-bold {{ $net >= 0 ? 'text-blue-700' : 'text-red-700' }}">
                            {{ $net != 0 ? number_format($net, 0) : '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                    <tr>
                        <td class="px-6 py-3 font-bold text-gray-800">Grand Total</td>
                        <td class="px-6 py-3 text-right font-bold text-emerald-700">{{ number_format($totalIncome, 0) }}</td>
                        <td class="px-6 py-3 text-right font-bold text-red-600">{{ number_format($totalExpense, 0) }}</td>
                        <td class="px-6 py-3 text-right font-bold text-blue-700">{{ number_format($totalIncome - $totalExpense, 0) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Member × Month Matrix --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Member Deposit Matrix</h3>
            <p class="text-xs text-gray-400 mt-0.5">Month-wise deposit amounts per member (BDT)</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm" style="min-width:{{ 200 + count($months) * 90 }}px;">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold sticky left-0 bg-gray-800 z-10">Member</th>
                        <th class="px-3 py-3 text-center font-semibold text-xs" style="min-width:60px;">Code</th>
                        @foreach($months as $label)
                        <th class="px-3 py-3 text-right font-semibold text-xs whitespace-nowrap" style="min-width:80px;">{{ $label }}</th>
                        @endforeach
                        <th class="px-4 py-3 text-right font-semibold bg-gray-700" style="min-width:90px;">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($members as $m)
                    @php
                        $memberTotal = collect($months)->sum(fn($label) => $matrix[$m->id][$label] ?? 0);
                    @endphp
                    <tr class="hover:bg-emerald-50/50 {{ $memberTotal == 0 ? 'opacity-50' : '' }}">
                        <td class="px-4 py-2.5 font-medium text-gray-800 sticky left-0 bg-white hover:bg-emerald-50/50">
                            {{ $m->user->name }}
                        </td>
                        <td class="px-3 py-2.5 text-center text-xs text-gray-400 font-mono">{{ $m->member_code }}</td>
                        @foreach($months as $label)
                        @php $amt = $matrix[$m->id][$label] ?? 0; @endphp
                        <td class="px-3 py-2.5 text-right text-xs {{ $amt > 0 ? 'font-semibold text-emerald-700 bg-emerald-50/40' : 'text-gray-300' }}">
                            {{ $amt > 0 ? number_format($amt, 0) : '—' }}
                        </td>
                        @endforeach
                        <td class="px-4 py-2.5 text-right font-bold text-gray-900 bg-gray-50">
                            {{ $memberTotal > 0 ? number_format($memberTotal, 0) : '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t-2 border-emerald-400 bg-emerald-50">
                    <tr>
                        <td class="px-4 py-3 font-bold text-emerald-800 sticky left-0 bg-emerald-50">Monthly Total</td>
                        <td class="px-3 py-3"></td>
                        @foreach($months as $label)
                        <td class="px-3 py-3 text-right font-bold text-emerald-800 text-xs">
                            {{ number_format($monthlyTotals[$label]['income'] ?? 0, 0) }}
                        </td>
                        @endforeach
                        <td class="px-4 py-3 text-right font-bold text-emerald-800 bg-emerald-100">
                            {{ number_format($totalIncome, 0) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Expense Breakdown --}}
    @if($expenseBreakdown->count())
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Expense Breakdown by Category</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Category</th>
                    <th class="px-6 py-3 text-right font-semibold text-gray-600">Total (৳)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($expenseBreakdown as $e)
                <tr>
                    <td class="px-6 py-3 text-gray-700">{{ \App\Models\Expense::categories()[$e->category] ?? ucfirst($e->category) }}</td>
                    <td class="px-6 py-3 text-right font-semibold text-red-600">{{ number_format($e->total, 0) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="border-t-2 border-gray-200 bg-red-50">
                <tr>
                    <td class="px-6 py-3 font-bold text-gray-800">Total</td>
                    <td class="px-6 py-3 text-right font-bold text-red-700">{{ number_format($totalExpense, 0) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

</div>
@endsection
