<footer class="border-t border-gray-200 bg-white">
    <div class="container-app flex flex-col items-center justify-between gap-4 py-8 sm:flex-row">
        <p class="text-sm text-gray-500">
            &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
        </p>

        <nav class="flex items-center gap-6 text-sm" aria-label="Footer">
            <a href="{{ route('home') }}" class="text-gray-600 transition hover:text-brand-600">Home</a>
            @auth
                <a href="{{ route('dashboard') }}" class="text-gray-600 transition hover:text-brand-600">Dashboard</a>
            @endauth
            @guest
                <a href="{{ route('login') }}" class="text-gray-600 transition hover:text-brand-600">Login</a>
            @endguest
        </nav>
    </div>
</footer>
