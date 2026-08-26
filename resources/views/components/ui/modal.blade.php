@props(['name' => 'modal'])

<div x-data="{ open: false }" {{ $attributes }} class="inline-block">
    {{-- Trigger --}}
    <button type="button" @click="open = true" data-animate-hover
            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2">
        {{ $trigger }}
    </button>

    {{-- Modal panel --}}
    <div x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true" role="dialog">
        <!-- Backdrop -->
        <div x-show="open"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"
             @click="open = false"
             aria-hidden="true">
        </div>

        <!-- Panel -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="open"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                 @keydown.escape.window="open = false"
                 x-trap.inert.noscroll="open"
                 class="relative w-full max-w-lg rounded-2xl border border-gray-100 bg-white p-6 shadow-2xl">

                <div class="flex items-start justify-between gap-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $title ?? '' }}</h3>

                    <button type="button" @click="open = false"
                            class="rounded-md p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500"
                            aria-label="Close modal">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mt-4 text-sm leading-relaxed text-gray-600">
                    {{ $slot }}
                </div>

                @isset($footer)
                    <div class="mt-6 flex justify-end gap-3">
                        {{ $footer }}
                    </div>
                @endisset
            </div>
        </div>
    </div>
</div>
