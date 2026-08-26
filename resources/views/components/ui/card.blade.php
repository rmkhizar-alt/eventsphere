@props(['padding' => true])

@php
$paddingClasses = $padding ? 'p-6' : '';
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-gray-100 bg-white shadow-sm shadow-gray-200/50 '.$paddingClasses]) }}>
    {{ $slot }}
</div>
