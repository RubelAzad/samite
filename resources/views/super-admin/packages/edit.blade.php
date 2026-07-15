@extends('layouts.super-admin')

@section('title', 'Edit Package')

@section('content')
<div class="mt-6 max-w-xl">
    <h2 class="text-2xl font-bold text-gray-800 mb-1">Edit Package</h2>
    <p class="text-gray-500 text-sm mb-6">{{ $package->name }}</p>

    <form method="POST" action="{{ route('super-admin.packages.update', $package) }}" class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Package Name</label>
            <input type="text" name="name" value="{{ old('name', $package->name) }}" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Price</label>
                <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $package->price) }}" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Billing Cycle</label>
                <select name="billing_cycle" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="monthly" {{ old('billing_cycle', $package->billing_cycle) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="yearly" {{ old('billing_cycle', $package->billing_cycle) === 'yearly' ? 'selected' : '' }}>Yearly</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Member Limit</label>
                <input type="number" min="1" name="member_limit" value="{{ old('member_limit', $package->member_limit) }}" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-2">Included Features</label>
            <div class="grid grid-cols-2 gap-2">
                @foreach(\App\Http\Controllers\SuperAdmin\PackageController::FEATURES as $key => $label)
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="features[]" value="{{ $key }}"
                               {{ in_array($key, old('features', $package->features ?? [])) ? 'checked' : '' }}
                               class="rounded text-indigo-600">
                        {{ $label }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium transition text-sm">
                Save Changes
            </button>
            <a href="{{ route('super-admin.packages.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
