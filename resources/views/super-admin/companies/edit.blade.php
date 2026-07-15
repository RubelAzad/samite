@extends('layouts.super-admin')

@section('title', 'Edit Company')

@section('content')
<div class="mt-6 max-w-2xl">
    <h2 class="text-2xl font-bold text-gray-800 mb-1">Edit Company</h2>
    <p class="text-gray-500 text-sm mb-6">{{ $company->name }}</p>

    <form method="POST" action="{{ route('super-admin.companies.update', $company) }}" class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-600 mb-1">Company Name</label>
                <input type="text" name="name" value="{{ old('name', $company->name) }}" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $company->phone) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $company->email) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-600 mb-1">Address</label>
                <textarea name="address" rows="2"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('address', $company->address) }}</textarea>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium transition text-sm">
                Save Changes
            </button>
            <a href="{{ route('super-admin.companies.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">Cancel</a>
        </div>
    </form>

    <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-1">Subscription</h3>
        <p class="text-gray-500 text-xs mb-4">
            @if($company->subscriptionPackage)
                Currently on <strong>{{ $company->subscriptionPackage->name }}</strong>,
                valid through {{ $company->subscription_ends_at?->format('d M Y') ?? '—' }}.
            @else
                No active package assigned.
            @endif
        </p>

        <form method="POST" action="{{ route('super-admin.companies.subscription.store', $company) }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            @csrf
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-600 mb-1">Package</label>
                <select name="subscription_package_id" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Select a package</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}" {{ $company->subscription_package_id === $package->id ? 'selected' : '' }}>
                            {{ $package->name }} ({{ $package->price }}/{{ $package->billing_cycle }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Start Date</label>
                <input type="date" name="starts_at" value="{{ now()->toDateString() }}" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Amount</label>
                <input type="number" step="0.01" min="0" name="amount" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="sm:col-span-3">
                <label class="block text-sm font-medium text-gray-600 mb-1">Payment Reference (optional)</label>
                <input type="text" name="payment_reference" placeholder="e.g. bKash txn ID"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium transition text-sm">
                    Activate / Renew
                </button>
            </div>
        </form>

        @if($subscriptions->isNotEmpty())
        <div class="mt-6 border-t border-gray-100 pt-4">
            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-3">Payment History</h4>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500">
                        <th class="py-1.5 pr-4">Package</th>
                        <th class="py-1.5 pr-4">Period</th>
                        <th class="py-1.5 pr-4">Amount</th>
                        <th class="py-1.5 pr-4">Reference</th>
                        <th class="py-1.5 pr-4">Voucher</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($subscriptions as $sub)
                    <tr>
                        <td class="py-1.5 pr-4">{{ $sub->package->name }}</td>
                        <td class="py-1.5 pr-4">{{ $sub->starts_at->format('d M Y') }} – {{ $sub->ends_at->format('d M Y') }}</td>
                        <td class="py-1.5 pr-4">{{ $sub->amount }}</td>
                        <td class="py-1.5 pr-4 text-gray-500">{{ $sub->payment_reference ?? '—' }}</td>
                        <td class="py-1.5 pr-4">
                            <a href="{{ route('super-admin.subscriptions.voucher', $sub) }}" class="text-indigo-600 hover:text-indigo-800" title="Download Voucher">
                                <i class="fas fa-file-invoice"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
