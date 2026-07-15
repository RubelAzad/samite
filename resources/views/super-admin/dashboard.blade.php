@extends('layouts.super-admin')

@section('title', 'Dashboard')

@section('content')
<div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-gray-500 text-sm">Total Companies</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalCompanies }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-gray-500 text-sm">Active Companies</p>
        <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $activeCompanies }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-gray-500 text-sm">Total Users</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalUsers }}</p>
    </div>
</div>

<div class="mt-6">
    <a href="{{ route('super-admin.companies.create') }}"
       class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium transition text-sm">
        <i class="fas fa-plus"></i> Create Company
    </a>
</div>
@endsection
