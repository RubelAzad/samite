@extends('layouts.admin')

@section('title', 'Daily Report')

@section('content')
<div class="mt-6">
    <div class="flex items-center justify-between mb-6" x-data="{ exportOpen: false, exportDate: '{{ $date }}' }">
        <h2 class="text-2xl font-bold text-gray-800">Daily Report</h2>
        <button @click="exportOpen = true"
                class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">
            <i class="fas fa-download"></i> Download Report
        </button>

        {{-- Export Modal --}}
        <div x-show="exportOpen" x-cloak
             class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
             @click.self="exportOpen = false">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 overflow-hidden">
                <div class="bg-emerald-600 px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-file-download text-white text-lg"></i>
                        <h3 class="text-white font-bold text-base">Download Daily Report</h3>
                    </div>
                    <button @click="exportOpen = false" class="text-white/70 hover:text-white">
                        <i class="fas fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Select Date</label>
                        <input type="date" x-model="exportDate"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <a :href="'{{ route('admin.reports.daily.export') }}?format=excel&date=' + exportDate"
                           class="flex flex-col items-center gap-2 bg-green-50 hover:bg-green-100 border border-green-200 text-green-800 py-4 rounded-xl transition text-sm font-semibold">
                            <i class="fas fa-file-excel text-green-600 text-2xl"></i>
                            Download Excel
                            <span class="text-xs font-normal text-green-600">.xlsx file</span>
                        </a>
                        <a :href="'{{ route('admin.reports.daily.print') }}?date=' + exportDate"
                           target="_blank" @click="exportOpen = false"
                           class="flex flex-col items-center gap-2 bg-red-50 hover:bg-red-100 border border-red-200 text-red-800 py-4 rounded-xl transition text-sm font-semibold">
                            <i class="fas fa-file-pdf text-red-600 text-2xl"></i>
                            Download PDF
                            <span class="text-xs font-normal text-red-600">print & save</span>
                        </a>
                    </div>
                    <p class="text-xs text-gray-400 text-center">PDF opens a print-ready page. Select "Save as PDF" in the print dialog.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Selector -->
    <form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-6 flex items-center gap-3">
        <label class="text-sm font-medium text-gray-700">Select Date:</label>
        <input type="date" name="date" value="{{ $date }}"
               class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
        <button type="submit" class="bg-emerald-600 text-white px-5 py-2 rounded-lg text-sm">View Report</button>
    </form>

    <!-- Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-emerald-500">
            <p class="text-sm text-gray-500">Income Today</p>
            <p class="text-2xl font-bold text-emerald-700">৳{{ number_format($totalIncome, 2) }}</p>
            <p class="text-xs text-gray-400">{{ $deposits->count() }} deposit(s)</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-red-500">
            <p class="text-sm text-gray-500">Expense Today</p>
            <p class="text-2xl font-bold text-red-600">৳{{ number_format($totalExpense, 2) }}</p>
            <p class="text-xs text-gray-400">{{ $expenses->count() }} expense(s)</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-purple-500">
            <p class="text-sm text-gray-500">Net for {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>
            <p class="text-2xl font-bold {{ $net >= 0 ? 'text-emerald-700' : 'text-red-600' }}">
                {{ $net >= 0 ? '+' : '' }}৳{{ number_format($net, 2) }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Deposits -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Deposits</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Member</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Amount</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Method</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($deposits as $d)
                    <tr>
                        <td class="px-6 py-3">{{ $d->member->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-3 font-semibold text-emerald-700">৳{{ number_format($d->amount, 2) }}</td>
                        <td class="px-6 py-3 capitalize text-gray-600">{{ $d->payment_method }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-6 text-center text-gray-400">No deposits</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Expenses -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Expenses</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Title</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Category</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($expenses as $e)
                    <tr>
                        <td class="px-6 py-3">{{ $e->title }}</td>
                        <td class="px-6 py-3 capitalize text-gray-600">{{ $e->category }}</td>
                        <td class="px-6 py-3 font-semibold text-red-600">৳{{ number_format($e->amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-6 text-center text-gray-400">No expenses</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
