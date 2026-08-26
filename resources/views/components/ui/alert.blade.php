@props([
    'variant' => 'info',
    'dismissible' => false,
])

@php
$variants = [
    'success' => ['border-emerald-200 bg-emerald-50 text-emerald-800', 'M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z'],
    'error'   => ['border-red-200 bg-red-50 text-red-800', 'M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z'],
    'warning' => ['border-amber-200 bg-amber-50 text-amber-800', 'M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z'],
    'info'    => ['border-blue-200 bg-blue-50 text-blue-800', 'M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z'],
];
[$colorClasses, $iconPath] = $variants[$variant];
@endphp

<div x-data="{ visible: true }" {{ $attributes->merge(['class' => 'w-full']) }}>
    <div x-show="visible"
         role="alert"
         class="flex items-start gap-3 rounded-xl border p-4 text-sm {{ $colorClasses }}">

        <svg class="mt-0.5 h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path fill-rule="evenodd" d="{{ $iconPath }}" clip-rule="evenodd" />
        </svg>

        <div class="flex-1 leading-relaxed">{{ $slot }}</div>

        @if ($dismissible)
            <button type="button" @click="visible = false"
                    class="-m-1 rounded p-1 opacity-70 transition hover:opacity-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-current"
                    aria-label="Dismiss">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        @endif
    </div>
</div>
