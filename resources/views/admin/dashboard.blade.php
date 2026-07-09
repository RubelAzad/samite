@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="mt-6 space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4 col-span-1">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Members</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalMembers }}</p>
                <p class="text-xs text-green-600">{{ $activeMembers }} active</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4 col-span-1">
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-hand-holding-usd text-emerald-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Collected</p>
                <p class="text-2xl font-bold text-gray-800">৳{{ number_format($totalDeposits, 0) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4 col-span-1">
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-receipt text-red-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Expenses</p>
                <p class="text-2xl font-bold text-gray-800">৳{{ number_format($totalExpenses, 0) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4 col-span-1">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-wallet text-purple-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Net Balance</p>
                <p class="text-2xl font-bold text-gray-800">৳{{ number_format($netBalance, 0) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4 col-span-1 {{ $pendingDeposits > 0 ? 'border-l-4 border-yellow-400' : '' }}">
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Pending</p>
                <p class="text-2xl font-bold text-gray-800">{{ $pendingDeposits }}</p>
                <a href="{{ route('admin.deposits.index', ['status' => 'pending']) }}" class="text-xs text-yellow-600 hover:underline">Review →</a>
            </div>
        </div>
    </div>

    <!-- Chart + Recent -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Income vs Expense – {{ now()->year }}</h3>
            <canvas id="incomeExpenseChart" height="100"></canvas>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.members.create') }}"
                   class="flex items-center gap-3 p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition text-blue-700 text-sm font-medium">
                    <i class="fas fa-user-plus w-5"></i> Add New Member
                </a>
                <a href="{{ route('admin.deposits.create') }}"
                   class="flex items-center gap-3 p-3 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition text-emerald-700 text-sm font-medium">
                    <i class="fas fa-plus-circle w-5"></i> Record Deposit
                </a>
                <a href="{{ route('admin.expenses.create') }}"
                   class="flex items-center gap-3 p-3 bg-red-50 hover:bg-red-100 rounded-lg transition text-red-700 text-sm font-medium">
                    <i class="fas fa-minus-circle w-5"></i> Add Expense
                </a>
                <a href="{{ route('admin.deposits.index', ['status' => 'pending']) }}"
                   class="flex items-center gap-3 p-3 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition text-yellow-700 text-sm font-medium">
                    <i class="fas fa-check-circle w-5"></i> Review Pending ({{ $pendingDeposits }})
                </a>
                <a href="{{ route('admin.reports.monthly') }}"
                   class="flex items-center gap-3 p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition text-purple-700 text-sm font-medium">
                    <i class="fas fa-chart-bar w-5"></i> Monthly Report
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Deposits -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Deposits</h3>
                <a href="{{ route('admin.deposits.index') }}" class="text-sm text-emerald-600 hover:underline">View all</a>
            </div>
            <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600 text-xs">Member</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600 text-xs">Amount</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600 text-xs">Status</th>
                    </tr>
                </thead>
                <tbody>
            @forelse($recentDeposits as $deposit)
            <tr class="border-b border-gray-100">
                <td class="px-3 py-2">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-gray-200 rounded-full flex items-center justify-center text-xs font-bold text-gray-600 flex-shrink-0">
                            {{ strtoupper(substr($deposit->member->user->name ?? '?', 0, 1)) }}
                        </div>
                        <span class="text-sm text-gray-800">{{ $deposit->member->user->name ?? 'N/A' }}</span>
                    </div>
                </td>
                <td class="px-3 py-2 text-sm font-semibold text-gray-800">৳{{ number_format($deposit->amount, 0) }}</td>
                <td class="px-3 py-2">
                    <span class="text-xs px-2 py-0.5 rounded-full
                        {{ $deposit->status === 'approved' ? 'bg-green-100 text-green-700' : ($deposit->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                        {{ ucfirst($deposit->status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr><td colspan="3" class="px-3 py-4 text-center text-gray-400 text-sm">No deposits yet</td></tr>
            @endforelse
                </tbody>
            </table>
        </div>

        <!-- Recent Expenses -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Expenses</h3>
                <a href="{{ route('admin.expenses.index') }}" class="text-sm text-emerald-600 hover:underline">View all</a>
            </div>
            <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600 text-xs">Title</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600 text-xs">Amount</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($recentExpenses as $expense)
                <tr class="border-b border-gray-100">
                    <td class="px-3 py-2">
                        <p class="text-sm font-medium text-gray-800">{{ $expense->title }}</p>
                        <p class="text-xs text-gray-400">{{ $expense->date->format('d M Y') }} · {{ ucfirst($expense->category) }}</p>
                    </td>
                    <td class="px-3 py-2 text-sm font-semibold text-red-600">– ৳{{ number_format($expense->amount, 0) }}</td>
                </tr>
                @empty
                <tr><td colspan="2" class="px-3 py-4 text-center text-gray-400 text-sm">No expenses yet</td></tr>
                @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const months = @json(collect($months)->pluck('month'));
const income  = @json(collect($months)->pluck('income'));
const expense = @json(collect($months)->pluck('expense'));

new Chart(document.getElementById('incomeExpenseChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [
            { label: 'Income', data: income, backgroundColor: 'rgba(16,185,129,0.8)', borderRadius: 6 },
            { label: 'Expense', data: expense, backgroundColor: 'rgba(239,68,68,0.8)', borderRadius: 6 },
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
