@extends('layouts.super-admin')

@section('title', 'Yearly Revenue Report')

@section('content')
<div class="mt-6">
    @include('super-admin.reports._nav')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Yearly Revenue Report</h2>
        @include('super-admin.reports._export-buttons', ['route' => 'super-admin.reports.yearly.export', 'params' => ['year' => $year]])
    </div>

    <form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-6 flex flex-wrap gap-3 items-center">
        <label class="text-sm text-gray-600">Year</label>
        <input type="number" name="year" value="{{ $year }}" min="2020" max="2100"
               class="border border-gray-300 rounded-lg px-4 py-2 text-sm w-28 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded-lg text-sm hover:bg-gray-700">
            <i class="fas fa-search mr-1"></i> View
        </button>
    </form>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Month</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Revenue</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($monthlyTotals as $row)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">{{ $row['month'] }} {{ $year }}</td>
                    <td class="px-6 py-4">{{ number_format($row['total'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="border-t border-gray-200 font-semibold text-gray-800">
                    <td class="px-6 py-3">Total</td>
                    <td class="px-6 py-3">{{ number_format($total, 2) }}</td>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>
</div>
@endsection
