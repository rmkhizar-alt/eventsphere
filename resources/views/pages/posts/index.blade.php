<x-app-layout>
    @section('title', 'Posts')

    <section class="py-16" data-scroll-reveal>
        <div class="container-app">
            <div class="mx-auto max-w-2xl text-center">
                <h1 class="section-title">Posts</h1>
                <p class="section-subtitle">
                    Sample listing page — pagination aur published scope ke sath.
                </p>
            </div>

            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($posts as $post)
                    <x-ui.card class="flex flex-col transition hover:-translate-y-1 hover:shadow-lg">
                        <span class="text-xs font-medium uppercase tracking-wide text-brand-600">
                            {{ $post->published_at?->format('M j, Y') ?? 'Draft' }}
                        </span>
                        <h2 class="mt-2 text-lg font-semibold leading-snug text-gray-900">
                            <a href="{{ route('posts.show', $post) }}" class="transition hover:text-brand-600">
                                {{ $post->title }}
                            </a>
                        </h2>
                        <p class="mt-3 flex-1 text-sm leading-relaxed text-gray-600">
                            {{ $post->excerpt }}
                        </p>
                        <div class="mt-4 border-t border-gray-100 pt-4 text-xs text-gray-500">
                            By {{ $post->author->name }}
                        </div>
                    </x-ui.card>
                @empty
                    <div class="col-span-full">
                        <x-ui.alert variant="info">
                            Abhi koi post publish nahi hui. Seeder chalao: <code>php artisan db:seed</code>
                        </x-ui.alert>
                    </div>
                @endforelse
            </div>

            <div class="mt-10">
                {{ $posts->links() }}
            </div>
        </div>
    </section>
</x-app-layout>
