<x-app-layout>
    <div class="max-w-7xl mx-auto py-8">
        <section class="mb-12 rounded-2xl overflow-hidden bg-gradient-to-br from-gray-50 to-white">
            <div class="relative h-[400px] w-full max-w-3xl mx-auto py-12 text-center">
                <h1 class="font-display text-3xl md:text-4xl font-bold text-gray-900 mb-4 tracking-tighter">
                    Live Events Directory
                </h1>
                <p class="text-lg text-gray-500 mb-8 max-w-xl">
                    Browse upcoming approved events. Filter by category, search by keyword, and register in one click.
                </p>
            </div>
        </section>

        <section class="mb-12" id="events-directory">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="events-grid">
                <div class="col-span-full py-12 text-center">
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
                const grid = document.getElementById('events-grid');
                const noEvents = document.getElementById('no-events');
                if (events.length === 0) { if (noEvents) noEvents.style.display = 'block'; return; }
                if (noEvents) noEvents.style.display = 'none';
                grid.innerHTML = events.map((e, i) => `
                    <div class="border rounded-xl overflow-hidden hover:shadow-lg transition-all duration-300">
                        <div class="relative h-48 w-full overflow-hidden bg-white">
                            <img src="/media/${e.slug}/banner.jpg" alt="${e.title}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-5">
                            <h3 class="font-semibold text-gray-900 truncate">${e.title}</h3>
                            <p class="text-sm text-gray-500 mt-1">${e.venue}</p>
                            <div class="mt-3 flex items-center gap-2 text-xs text-gray-400">
                                <span class="capitalize">${e.category}</span>
                                <span>•</span>
                                <span>${e.event_date}</span>
                            </div>
                        </div>
                    </div>
                `).join('');
            } catch (err) { console.error(err); }
        });
    </script>
</x-app-layout>
