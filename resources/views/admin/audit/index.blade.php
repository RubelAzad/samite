@extends('layouts.admin')

@section('title', 'Audit Log')

@section('content')
<div class="mt-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Audit Log</h2>
        <p class="text-gray-500 text-sm">Complete audit trail of all system actions</p>
    </div>

    <!-- Filters -->
    <form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-6 flex flex-wrap gap-3">
        <select name="action" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="">All Actions</option>
            <option value="login" {{ request('action') === 'login' ? 'selected' : '' }}>Login</option>
            <option value="logout" {{ request('action') === 'logout' ? 'selected' : '' }}>Logout</option>
            <option value="create" {{ request('action') === 'create' ? 'selected' : '' }}>Create</option>
            <option value="update" {{ request('action') === 'update' ? 'selected' : '' }}>Update</option>
            <option value="approve" {{ request('action') === 'approve' ? 'selected' : '' }}>Approve</option>
            <option value="reject" {{ request('action') === 'reject' ? 'selected' : '' }}>Reject</option>
            <option value="delete" {{ request('action') === 'delete' ? 'selected' : '' }}>Delete</option>
        </select>
        <select name="entity_type" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="">All Entities</option>
            <option value="user" {{ request('entity_type') === 'user' ? 'selected' : '' }}>User</option>
            <option value="member" {{ request('entity_type') === 'member' ? 'selected' : '' }}>Member</option>
            <option value="deposit" {{ request('entity_type') === 'deposit' ? 'selected' : '' }}>Deposit</option>
            <option value="expense" {{ request('entity_type') === 'expense' ? 'selected' : '' }}>Expense</option>
        </select>
        <input type="date" name="from" value="{{ request('from') }}" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
        <input type="date" name="to" value="{{ request('to') }}" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
        <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded-lg text-sm">Filter</button>
        <a href="{{ route('admin.audit.index') }}" class="bg-gray-100 text-gray-600 px-5 py-2 rounded-lg text-sm">Reset</a>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Time</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">User</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Action</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Entity</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Description</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-xs text-gray-400 whitespace-nowrap">{{ $log->created_at->format('d M Y H:i') }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $log->user->name ?? 'System' }}</td>
                    <td class="px-6 py-3">
                        @php
                        $colors = [
                            'login' => 'bg-blue-100 text-blue-700',
                            'logout' => 'bg-gray-100 text-gray-600',
                            'create' => 'bg-green-100 text-green-700',
                            'update' => 'bg-yellow-100 text-yellow-700',
                            'approve' => 'bg-emerald-100 text-emerald-700',
                            'reject' => 'bg-red-100 text-red-700',
                            'delete' => 'bg-red-200 text-red-800',
                            'toggle_status' => 'bg-purple-100 text-purple-700',
                        ];
                        $color = $colors[$log->action] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <span class="text-xs px-2 py-1 rounded-full font-medium {{ $color }}">
                            {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-600 capitalize">{{ $log->entity_type }}{{ $log->entity_id ? " #$log->entity_id" : '' }}</td>
                    <td class="px-6 py-3 text-gray-600 max-w-xs truncate">{{ $log->description }}</td>
                    <td class="px-6 py-3 text-xs font-mono text-gray-400">{{ $log->ip_address }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        <i class="fas fa-shield-alt text-3xl mb-2 block"></i>
                        No audit logs found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
