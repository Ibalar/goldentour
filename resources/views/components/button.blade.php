@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
])

@php
    $variants = [
        'primary' => 'bg-primary-600 text-white shadow-lg shadow-primary-900/20 hover:bg-primary-700',
        'secondary' => 'bg-secondary-900 text-white hover:bg-secondary-800',
        'outline' => 'border border-secondary-300 bg-white text-secondary-900 hover:border-primary-300 hover:text-primary-700',
    ];

    $sizes = [
        'sm' => 'px-4 py-2.5 text-sm',
        'md' => 'px-6 py-3 text-sm sm:text-base',
        'lg' => 'px-8 py-4 text-base sm:text-lg',
    ];

    $classes = 'inline-flex items-center justify-center gap-2 rounded-2xl font-semibold transition duration-200 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-offset-2';
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md'])]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md'])]) }}>
        {{ $slot }}
    </button>
@endif
