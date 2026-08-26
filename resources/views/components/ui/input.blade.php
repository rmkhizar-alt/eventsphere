@props([
    'name',
    'label' => null,
    'type' => 'text',
    'hint' => null,
])

@php
// Validation error display built-in: $errors se name ke mutabiq error uthata hai
$error = $errors->first($name);
@endphp

<div class="space-y-1.5">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
            {{ $label }}
        </label>
    @endif

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        {{ $attributes->merge(['class' => 'block w-full rounded-lg border-gray-300 text-sm shadow-sm transition focus:border-brand-500 focus:ring-brand-500 '.($error ? 'border-red-400 focus:border-red-500 focus:ring-red-500' : '')]) }}
        aria-invalid="{{ $error ? 'true' : 'false' }}"
        aria-describedby="{{ $error ? $name.'-error' : ($hint ? $name.'-hint' : null) }}"
    >

    @if ($error)
        <p id="{{ $name }}-error" class="flex items-center gap-1 text-sm text-red-600">
            <svg class="h-4 w-4 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            {{ $error }}
        </p>
    @elseif ($hint)
        <p id="{{ $name }}-hint" class="text-sm text-gray-500">{{ $hint }}</p>
    @endif
</div>
