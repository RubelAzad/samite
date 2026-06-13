@extends('layouts.admin')

@section('title', 'Expenses')

@section('content')
<div class="mt-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Expenses</h2>
            <p class="text-gray-500 text-sm">Track all operational costs</p>
        </div>
        <a href="{{ route('admin.expenses.create') }}"
           class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg font-medium transition text-sm">
            <i class="fas fa-plus-circle"></i> Add Expense
        </a>
    </div>

    <!-- Filters -->
    <form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-6 flex flex-wrap gap-3">
        <select name="category" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            <option value="">All Categories</option>
            @foreach($categories as $key => $label)
            <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <input type="date" name="from" value="{{ request('from') }}" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
        <input type="date" name="to" value="{{ request('to') }}" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
        <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded-lg text-sm">
            <i class="fas fa-search mr-1"></i> Filter
        </button>
        <a href="{{ route('admin.expenses.index') }}" class="bg-gray-100 text-gray-600 px-5 py-2 rounded-lg text-sm">Reset</a>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Title</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Category</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Amount</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Date</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">By</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($expenses as $expense)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-800">{{ $expense->title }}</p>
                        @if($expense->description)
                        <p class="text-xs text-gray-400 truncate max-w-xs">{{ $expense->description }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs px-2 py-1 rounded-full bg-orange-100 text-orange-700 font-medium">
                            {{ $categories[$expense->category] ?? ucfirst($expense->category) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold text-red-600">৳{{ number_format($expense->amount, 0) }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $expense->date->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $expense->createdBy->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.expenses.show', $expense) }}" class="text-blue-600 hover:text-blue-800 p-1">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.expenses.edit', $expense) }}" class="text-yellow-600 hover:text-yellow-800 p-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.expenses.destroy', $expense) }}" class="inline"
                                  onsubmit="return confirm('Delete this expense?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 p-1">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        <i class="fas fa-receipt text-3xl mb-2 block"></i>
                        No expenses found
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($expenses->count() > 0)
            <tfoot class="bg-gray-50 border-t border-gray-200">
                <tr>
                    <td colspan="2" class="px-6 py-3 font-semibold text-gray-600">Page Total</td>
                    <td class="px-6 py-3 font-bold text-red-600">৳{{ number_format($expenses->sum('amount'), 0) }}</td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
            @endif
        </table>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $expenses->links() }}
        </div>
    </div>
</div>
@endsection
