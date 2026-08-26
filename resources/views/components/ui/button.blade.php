@props([
    'variant' => 'primary',
    'href' => null,
    'type' => 'button',
])

@php
$baseClasses = 'inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-semibold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50';

$variants = [
    // Gradient primary + glow shadow
    'primary' => 'bg-gradient-to-r from-brand-600 to-brand-500 text-white shadow-md shadow-brand-500/25 hover:shadow-lg hover:shadow-brand-500/40',
    // Outlined secondary
    'secondary' => 'border border-gray-300 bg-white text-gray-700 shadow-sm hover:bg-gray-50 hover:text-gray-900',
    'danger' => 'bg-red-600 text-white shadow-md shadow-red-500/20 hover:bg-red-500',
];

$classes = $baseClasses.' '.$variants[$variant];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
