<x-app-layout>
    @section('title', $post->title)

    <article class="py-16" data-scroll-reveal>
        <div class="container-app max-w-3xl">
            <a href="{{ route('posts.index') }}" class="inline-flex items-center gap-1 text-sm font-medium text-brand-600 transition hover:text-brand-500">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                All posts
            </a>

            <header class="mt-6">
                <span class="text-xs font-medium uppercase tracking-wide text-brand-600">
                    {{ $post->published_at?->format('F j, Y') ?? 'Draft' }}
                </span>
                <h1 class="mt-2 text-4xl font-bold tracking-tight text-gray-900">
                    {{ $post->title }}
                </h1>
                <p class="mt-4 text-sm text-gray-500">
                    By {{ $post->author->name }}
                </p>
            </header>

            <div class="mt-10 space-y-6 text-base leading-8 text-gray-700">
                {!! collect(explode("\n\n", $post->body))
                    ->map(fn ($p) => '<p>'.e(trim($p)).'</p>')
                    ->implode('') !!}
            </div>
        </div>
    </article>
</x-app-layout>
