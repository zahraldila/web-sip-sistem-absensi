@props(['status' => ''])

@php
    $normalized = strtolower($status);

    if (str_contains($normalized, 'hadir')) {
        $badgeClass = 'inline-block px-4 py-1.5 rounded-full text-xs font-semibold text-green-700 bg-green-100';
    } elseif (str_contains($normalized, 'terlambat')) {
        $badgeClass = 'inline-block px-4 py-1.5 rounded-full text-xs font-semibold text-amber-700 bg-amber-100';
    } elseif (str_contains($normalized, 'tidak hadir') || str_contains($normalized, 'alpa') || str_contains($normalized, 'izin')) {
        $badgeClass = 'inline-block px-4 py-1.5 rounded-full text-xs font-semibold text-rose-700 bg-rose-100';
    } else {
        $badgeClass = 'inline-block px-4 py-1.5 rounded-full text-xs font-semibold text-slate-700 bg-slate-100';
    }
@endphp

<span class="{{ $badgeClass }}">
    {{ $status ?: 'Unknown' }}
</span>
