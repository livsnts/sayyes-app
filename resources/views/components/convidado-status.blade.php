@props(['status'])

@php
    $classes = match ($status) {
        'CONFIRMADO' => 'text-success',
        'RECUSADO' => 'text-danger',
        default => 'text-warning',
    };
@endphp

<span {{ $attributes->merge(['class' => 'font-semibold ' . $classes]) }}>
    {{ ucfirst(strtolower($status)) }}
</span>