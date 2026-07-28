@props([
    'variant' => 'primary'
])

@php
$baseClasses = '
    px-8 py-2
    mt-2
    rounded-lg
    transition-colors
    duration-200
    cursor-pointer
    disabled:cursor-not-allowed
    disabled:bg-gray-400
';

$variantClasses = [
    'primary' => 'bg-primary text-white hover:bg-primary-dark',
    'secondary' => 'bg-secondary text-white hover:opacity-90',
    'outline' => 'border border-2 border-primary text-primary bg-transparent hover:bg-primary hover:text-white',
    'danger' => 'bg-danger text-white hover:bg-danger-dark'
];
@endphp

<button
    {{
        $attributes->merge([
            'class' => $baseClasses . ' ' . ($variantClasses[$variant] ?? $variantClasses['primary'])
        ])
    }}
>
    {{ $slot }}
</button>