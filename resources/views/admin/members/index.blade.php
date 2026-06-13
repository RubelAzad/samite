@extends('layouts.admin')

@section('title', 'Members')

@section('content')
<div class="mt-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Members</h2>
            <p class="text-gray-500 text-sm">Manage all group members</p>
        </div>
        <a href="{{ route('admin.members.create') }}"
           class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg font-medium transition text-sm">
            <i class="fas fa-user-plus"></i> Add Member
        </a>
    </div>

    <!-- Filters -->
    <form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-6 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..."
               class="flex-1 min-w-48 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
        <select name="status" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded-lg text-sm hover:bg-gray-700">
            <i class="fas fa-search mr-1"></i> Filter
        </button>
        <a href="{{ route('admin.members.index') }}" class="bg-gray-100 text-gray-600 px-5 py-2 rounded-lg text-sm hover:bg-gray-200">
            Reset
        </a>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Member</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Code</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Phone</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Join Date</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Plan</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Status</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($members as $member)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-700 font-bold text-sm">
                                {{ strtoupper(substr($member->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $member->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $member->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-mono text-gray-600">{{ $member->member_code }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $member->phone ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $member->join_date->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700 font-medium">
                            {{ ucfirst($member->deposit_plan) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs px-2 py-1 rounded-full font-medium
                            {{ $member->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($member->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.members.show', $member) }}"
                               class="text-blue-600 hover:text-blue-800 p-1" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.members.edit', $member) }}"
                               class="text-yellow-600 hover:text-yellow-800 p-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.members.toggle-status', $member) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="{{ $member->status === 'active' ? 'text-red-500 hover:text-red-700' : 'text-green-500 hover:text-green-700' }} p-1"
                                        title="{{ $member->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                    <i class="fas {{ $member->status === 'active' ? 'fa-toggle-on' : 'fa-toggle-off' }} text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                        <i class="fas fa-users text-3xl mb-2 block"></i>
                        No members found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $members->links() }}
        </div>
    </div>
</div>
@endsection
