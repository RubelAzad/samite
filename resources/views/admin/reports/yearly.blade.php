@extends('layouts.admin')

@section('title', 'Yearly Report')

@section('content')
<div class="mt-6">
    <div class="flex items-center justify-between mb-6" x-data="{ exportOpen: false, exportYear: '{{ $year }}' }">
        <h2 class="text-2xl font-bold text-gray-800">Yearly Report – {{ $year }}</h2>
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
                        <h3 class="text-white font-bold text-base">Download Yearly Report</h3>
                    </div>
                    <button @click="exportOpen = false" class="text-white/70 hover:text-white">
                        <i class="fas fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Select Year</label>
                        <select x-model="exportYear"
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            @for($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <a :href="'{{ route('admin.reports.yearly.export') }}?format=excel&year=' + exportYear"
                           class="flex flex-col items-center gap-2 bg-green-50 hover:bg-green-100 border border-green-200 text-green-800 py-4 rounded-xl transition text-sm font-semibold">
                            <i class="fas fa-file-excel text-green-600 text-2xl"></i>
                            Download Excel
                            <span class="text-xs font-normal text-green-600">.xlsx file</span>
                        </a>
                        <a :href="'{{ route('admin.reports.yearly.print') }}?year=' + exportYear"
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

    <form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-6 flex items-center gap-3">
        <label class="text-sm font-medium text-gray-700">Year:</label>
        <select name="year" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            @for($y = now()->year; $y >= now()->year - 5; $y--)
            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
        <button type="submit" class="bg-emerald-600 text-white px-5 py-2 rounded-lg text-sm">View Report</button>
    </form>

    <!-- Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-emerald-500">
            <p class="text-sm text-gray-500">Total Income {{ $year }}</p>
            <p class="text-2xl font-bold text-emerald-700">৳{{ number_format($totalIncome, 0) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-red-500">
            <p class="text-sm text-gray-500">Total Expense {{ $year }}</p>
            <p class="text-2xl font-bold text-red-600">৳{{ number_format($totalExpense, 0) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-purple-500">
            <p class="text-sm text-gray-500">Net Savings {{ $year }}</p>
            <p class="text-2xl font-bold text-purple-700">৳{{ number_format($totalIncome - $totalExpense, 0) }}</p>
        </div>
    </div>

    <!-- Monthly Chart -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h3 class="font-semibold text-gray-800 mb-4">Monthly Income vs Expense</h3>
        <canvas id="yearChart" height="80"></canvas>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Member Ranking -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Member Contribution Ranking</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Rank</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Member</th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-600">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($memberContributions as $i => $m)
                    <tr>
                        <td class="px-6 py-3">
                            @if($i < 3)
                            <span class="text-lg">{{ ['🥇','🥈','🥉'][$i] }}</span>
                            @else
                            <span class="text-gray-400 font-mono">{{ $i + 1 }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 font-medium text-gray-800">{{ $m->user->name }}</td>
                        <td class="px-6 py-3 text-right font-bold text-emerald-700">৳{{ number_format($m->year_total, 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Expense Trends -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Expense Trends by Category</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Category</th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-600">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($expenseTrends as $et)
                    <tr>
                        <td class="px-6 py-3 capitalize text-gray-700">{{ \App\Models\Expense::categories()[$et->category] ?? $et->category }}</td>
                        <td class="px-6 py-3 text-right font-semibold text-red-600">৳{{ number_format($et->total, 0) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="px-6 py-6 text-center text-gray-400">No expenses for {{ $year }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
new Chart(document.getElementById('yearChart'), {
    type: 'line',
    data: {
        labels: @json(collect($monthlyData)->pluck('month')),
        datasets: [
            {
                label: 'Income',
                data: @json(collect($monthlyData)->pluck('income')),
                borderColor: 'rgb(16,185,129)',
                backgroundColor: 'rgba(16,185,129,0.1)',
                fill: true,
                tension: 0.4,
            },
            {
                label: 'Expense',
                data: @json(collect($monthlyData)->pluck('expense')),
                borderColor: 'rgb(239,68,68)',
                backgroundColor: 'rgba(239,68,68,0.1)',
                fill: true,
                tension: 0.4,
            },
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: { y: { beginAtZero: true, ticks: { callback: v => '৳' + v.toLocaleString() } } }
    }
});
</script>
@endpush
