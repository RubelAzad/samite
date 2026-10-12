@extends('layouts.admin')

@section('title', 'Bank Accounts')

@section('content')
<div class="mt-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Bank Accounts</h2>
            <p class="text-gray-500 text-sm">Manage bank accounts for withdrawals and deposits</p>
        </div>
        <a href="{{ route('admin.bank-accounts.create') }}"
           class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg font-medium transition text-sm">
            <i class="fas fa-plus-circle"></i> Add Bank Account
        </a>
    </div>

    <!-- Filters -->
    <form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-6 flex flex-wrap gap-3">
        <select name="status" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded-lg text-sm">
            <i class="fas fa-search mr-1"></i> Filter
        </button>
        <a href="{{ route('admin.bank-accounts.index') }}" class="bg-gray-100 text-gray-600 px-5 py-2 rounded-lg text-sm">Reset</a>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">#</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Account Name</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Bank</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Account No</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Type</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Opening Balance</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Current Balance</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Status</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bankAccounts as $account)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-400 font-mono text-xs">#{{ $account->id }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $account->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $account->bank_name }}</td>
                    <td class="px-6 py-4 font-mono text-gray-600">{{ $account->account_number }}</td>
                    <td class="px-6 py-4 capitalize text-gray-600">{{ $account->type }}</td>
                    <td class="px-6 py-4 text-gray-600">৳{{ number_format($account->opening_balance, 2) }}</td>
                    <td class="px-6 py-4 font-bold text-gray-800">৳{{ number_format($account->current_balance, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="text-xs px-2 py-1 rounded-full font-medium
                            {{ $account->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $account->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4" x-data="{ open: false }">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.bank-accounts.show', $account) }}" class="text-blue-600 hover:text-blue-800 p-1" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.bank-accounts.edit', $account) }}" class="text-indigo-600 hover:text-indigo-800 p-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.bank-accounts.destroy', $account) }}" class="inline" onsubmit="return confirm('Delete this bank account?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 p-1" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                        <i class="fas fa-university text-3xl mb-2 block"></i>
                        No bank accounts found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $bankAccounts->links() }}
        </div>
        </div>
    </div>
</div>
@endsection