@extends('layouts.super-admin')

@section('title', 'Create Company')

@section('content')
<div class="mt-6 max-w-2xl">
    <h2 class="text-2xl font-bold text-gray-800 mb-1">Create Company</h2>
    <p class="text-gray-500 text-sm mb-6">Set up a new tenant and its first admin account.</p>

    <form method="POST" action="{{ route('super-admin.companies.store') }}" class="bg-white rounded-xl shadow-sm p-6 space-y-5">
        @csrf

        <div>
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Company Details</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Company Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Address</label>
                    <textarea name="address" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('address') }}</textarea>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Subscription</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Package</label>
                    <select name="subscription_package_id" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select a package</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" data-price="{{ $package->price }}" {{ old('subscription_package_id') == $package->id ? 'selected' : '' }}>
                                {{ $package->name }} ({{ $package->price }}/{{ $package->billing_cycle }}, {{ $package->member_limit }} members)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Amount Received</label>
                    <input type="number" step="0.01" min="0" name="amount" value="{{ old('amount') }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">First Admin Account</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Admin Name</label>
                    <input type="text" name="admin_name" value="{{ old('admin_name') }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Admin Email</label>
                    <input type="email" name="admin_email" value="{{ old('admin_email') }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Admin Password</label>
                    <input type="password" name="admin_password" required minlength="8"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium transition text-sm">
                Create Company
            </button>
            <a href="{{ route('super-admin.companies.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
