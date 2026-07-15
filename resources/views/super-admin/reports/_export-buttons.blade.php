@php
    $query = array_filter($params ?? []);
@endphp
<div class="flex items-center gap-2">
    <a href="{{ route($route, array_merge($query, ['format' => 'excel'])) }}"
       class="inline-flex items-center gap-2 bg-green-50 hover:bg-green-100 border border-green-200 text-green-800 px-4 py-2 rounded-lg text-sm font-medium transition">
        <i class="fas fa-file-excel text-green-600"></i> Excel
    </a>
    <a href="{{ route($route, array_merge($query, ['format' => 'pdf'])) }}"
       class="inline-flex items-center gap-2 bg-red-50 hover:bg-red-100 border border-red-200 text-red-800 px-4 py-2 rounded-lg text-sm font-medium transition">
        <i class="fas fa-file-pdf text-red-600"></i> PDF
    </a>
</div>
