<x-app-layout>
    <div class="container mx-auto py-8">
        <section class="mb-12">
            <div class="relative h-[600px] w-full rounded-2xl overflow-hidden bg-gradient-to-br from-gray-50 to-white">
                <video autoplay muted loop playsinline class="w-full h-full object-cover">
                    <source src="/videos/hero.mp4" type="video/mp4">
                </video>
                <div class="absolute inset-0 bg-black/40"></div>
                <div class="absolute inset-0 z-10 flex flex-col items-center justify-center text-center px-4">
                    <div class="relative w-full max-w-2xl">
                        <h1 class="font-display text-5xl md:text-6xl font-bold text-white mb-4 tracking-tighter">
                            WE DON'T
                            <span class="block">WAIT.</span>
                        </h1>
                        <p class="text-lg text-gray-200 mb-8 max-w-2xl">
                            Fast emergency response for your campus community. Instant notifications, real-time tracking, and seamless communication.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('posts.index') }}" class="px-8 py-3 text-lg rounded-lg bg-brand-600 text-white font-semibold hover:bg-brand-500 transition">
                                Browse Events
                            </a>
                            <a href="{{ route('login') }}" class="px-8 py-3 text-lg rounded-lg border-2 border-white text-white font-semibold hover:bg-white/10 transition">
                                Sign In
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Live Events</h2>
            <p class="text-gray-400 mb-6">Upcoming approved events</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-400" id="no-events">No events found. Check back soon!</p>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            try {
                const response = await fetch('/api/events');
                const result = await response.json();
                const events = result.data || [];
                if (events.length > 0) {
                    document.getElementById('no-events').style.display = 'none';
                }
            } catch (e) {}
        });
    </script>
</x-app-layout>
