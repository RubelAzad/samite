@extends('layouts.admin')

@section('title', 'Expense Detail')

@section('content')
<div class="mt-6 max-w-lg">
    <div class="mb-6">
        <a href="{{ route('admin.expenses.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left mr-1"></i> Back to Expenses
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">{{ $expense->title }}</h2>
                <span class="text-xs px-2 py-1 rounded-full bg-orange-100 text-orange-700 font-medium mt-1 inline-block">
                    {{ \App\Models\Expense::categories()[$expense->category] ?? ucfirst($expense->category) }}
                </span>
            </div>
            <p class="text-2xl font-bold text-red-600">৳{{ number_format($expense->amount, 2) }}</p>
        </div>

        <dl class="space-y-3 text-sm">
            <div class="flex justify-between py-2 border-b border-gray-100">
                <dt class="text-gray-500">Date</dt>
                <dd class="text-gray-800">{{ $expense->date->format('d F Y') }}</dd>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <dt class="text-gray-500">Recorded By</dt>
                <dd class="text-gray-800">{{ $expense->createdBy->name ?? 'N/A' }}</dd>
            </div>
            @if($expense->description)
            <div class="py-2 border-b border-gray-100">
                <dt class="text-gray-500 mb-1">Description</dt>
                <dd class="text-gray-800">{{ $expense->description }}</dd>
            </div>
            @endif
            @if($expense->attachment)
            <div class="py-2">
                <dt class="text-gray-500 mb-2">Receipt</dt>
                <dd>
                    <img src="{{ asset('storage/' . $expense->attachment) }}" alt="Receipt" class="max-w-full rounded-lg border border-gray-200">
                </dd>
            </div>
            @endif
        </dl>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('admin.expenses.edit', $expense) }}"
               class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-yellow-600">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
            <form method="POST" action="{{ route('admin.expenses.destroy', $expense) }}"
                  onsubmit="return confirm('Delete this expense?')">
                @csrf @method('DELETE')
                <button type="submit" class="bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-200">
                    <i class="fas fa-trash mr-1"></i> Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
