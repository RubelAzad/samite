@extends('layouts.super-admin')

@section('title', 'Monthly Revenue Report')

@section('content')
<div class="mt-6">
    @include('super-admin.reports._nav')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Monthly Revenue Report</h2>
        @include('super-admin.reports._export-buttons', ['route' => 'super-admin.reports.monthly.export', 'params' => ['month' => $month]])
    </div>

    <form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-6 flex flex-wrap gap-3 items-center">
        <label class="text-sm text-gray-600">Month</label>
        <input type="month" name="month" value="{{ $month }}"
               class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded-lg text-sm hover:bg-gray-700">
            <i class="fas fa-search mr-1"></i> View
        </button>
    </form>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Date</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Company</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Package</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Amount</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Status</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Voucher</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">{{ $payment->starts_at->format('d M Y') }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $payment->company->name ?? '—' }}</td>
                    <td class="px-6 py-4">{{ $payment->package->name ?? '—' }}</td>
                    <td class="px-6 py-4">{{ number_format($payment->amount, 2) }}</td>
                    <td class="px-6 py-4">
                        @if($payment->company)
                            @include('super-admin.partials.status-badge', ['company' => $payment->company])
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('super-admin.subscriptions.voucher', $payment) }}" class="text-indigo-600 hover:text-indigo-800" title="Download Voucher">
                            <i class="fas fa-file-invoice"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">No payments in {{ $month }}.</td></tr>
                @endforelse
            </tbody>
            @if($payments->isNotEmpty())
            <tfoot>
                <tr class="border-t border-gray-200 font-semibold text-gray-800">
                    <td class="px-6 py-3" colspan="3">Total</td>
                    <td class="px-6 py-3">{{ number_format($total, 2) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
            @endif
        </table>
        </div>
    </div>
</div>
@endsection
