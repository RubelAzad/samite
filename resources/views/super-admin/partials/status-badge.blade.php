@php
    $status = $company->subscriptionStatus();
    $classes = [
        'active'   => 'bg-emerald-50 text-emerald-700',
        'expiring' => 'bg-amber-50 text-amber-700',
        'expired'  => 'bg-red-50 text-red-700',
    ][$status];
    $labels = [
        'active'   => 'Active',
        'expiring' => 'Expiring Soon',
        'expired'  => 'Expired',
    ][$status];
@endphp
<span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $classes }}">{{ $labels }}</span>
