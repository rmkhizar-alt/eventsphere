<x-app-layout>

    <div class="py-10">
        <div class="container-app grid gap-8 lg:grid-cols-[16rem_1fr]">
            <!-- ============ SIDEBAR ============ -->
            <aside class="lg:sticky lg:top-24 lg:self-start" data-scroll-reveal>
                <x-ui.card :padding="false">
                    <nav class="space-y-1 p-3" aria-label="Dashboard">
                        @php
                            $sidebarLinks = [
                                ['label' => 'Overview', 'route' => 'dashboard', 'active' => request()->routeIs('dashboard'),
                                 'icon' => 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z'],
                                 ['label' => 'Posts', 'route' => 'posts.index', 'active' => request()->routeIs('posts.*'),
                                  'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z'],
                                 ['label' => 'Profile', 'route' => 'profile.edit', 'active' => request()->routeIs('profile.*'),
                                  'icon' => 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z'],
                             ];
                        @endphp

                        @foreach ($sidebarLinks as $link)
                            <a href="{{ route($link['route']) }}"
                               @class([
                                    'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition',
                                    'bg-brand-50 text-brand-700' => $link['active'],
                                    'text-gray-600 hover:bg-gray-100 hover:text-gray-900' => ! $link['active'],
                                ])
                                @if ($link['active']) aria-current="page" @endif>
                                 <svg class="h-5 w-5 shrink-0 {{ $link['active'] ? 'text-brand-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}" />
                                </svg>
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </x-ui.card>
            </aside>

            <!-- ============ CONTENT AREA ============ -->
            <main data-scroll-reveal>
                <header>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                        Welcome back, {{ $user->name }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-600">
                        EventSphere dashboard — your command center for college events
                    </p>
                </header>

                <section class="mt-8 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    <!-- Total Events Card -->
                    <x-ui.card>
                        <div class="p-6 flex flex-col items-start">
                            <p class="text-sm font-medium text-gray-500">Total Events</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalEvents }}</p>
                        </div>
                    </x-ui.card>

                    <!-- Total Participants Card -->
                    <x-ui.card>
                        <div class="p-6 flex flex-col items-start">
                            <p class="text-sm font-medium text-gray-500">Registered Participants</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalParticipants }}</p>
                        </div>
                    </x-ui.card>

                    <!-- Upcoming Events Card -->
                    <x-ui.card class="sm:col-span-2 xl:col-span-1">
                        <div class="p-6">
                            <p class="text-sm font-medium text-gray-500">Upcoming This Week</p>
                            <p class="mt-2 text-lg font-bold text-brand-600">{{ $upcomingThisWeek }}</p>
                        </div>
                    </x-ui.card>

                    <!-- Recent Activity Card -->
                    <x-ui.card>
                        <div class="p-6 h-64 flex flex-col justify-end">
                            <p class="text-sm font-medium text-gray-500">Recent Activity</p>
                            <p class="mt-2 text-xs text-gray-400">2 events posted, 15 registrations</p>
                        </div>
                    </x-ui.card>

                    <!-- Quick Stats Card -->
                    <x-ui.card class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="text-xs font-medium text-gray-500">This Month</p>
                            <p class="mt-1 text-lg font-bold text-gray-900">{{ $registrationsThisMonth }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500">Events Completed</p>
                            <p class="mt-1 text-lg font-bold text-brand-600">{{ $completedEvents }}</p>
                        </div>
                    </x-ui.card>
                </section>

                <!-- ============ QUICK ACTIONS ============ -->
                <section class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <x-ui.card>
                        <div class="p-6">
                            <div class="flex items-center gap-3">
                                <svg class="h-5 w-5 text-brand-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 4" />
                                </svg>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Post Event</h3>
                                    <p class="text-sm text-gray-500">Create and manage college events</h3>
                                </div>
                            </div>
                        </div>
                    </x-ui.card>

                    <x-ui.card>
                        <div class="p-6">
                            <div class="flex items-center gap-3">
                                <svg class="h-5 w-5 text-brand-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8h-8m4 4H8m6 8v6l-4 4" />
                                </svg>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Check Attendance</h3>
                                    <p class="text-sm text-gray-500">Mark participant attendance</p>
                                </div>
                            </div>
                        </div>
                    </x-ui.card>

                    <x-ui.card>
                        <div class="p-6">
                            <div class="flex items-center gap-3">
                                <svg class="h-5 w-5 text-brand-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v6l3-3m-3-3l3 3m-6-6h.08m-8.28-1.29a3 3 0 11-4.24-4.24l2.29-2.29m2.19-.28a3 3 0 114.24 4.24l-2.29 2.29M9 3v6l3 3m-3 3h6m-.08.28A3 3 0 015.95 15.03" />
                                </svg>
                                <div>
                                    <h3 class="font-semibold text-gray-900">View Certificates</h3>
                                    <p class="text-sm text-gray-500">Download participant certificates</p>
                                </div>
                            </div>
                        </div>
                    </x-ui.card>
                </section>
            </main>
        </div>
    </div>
</x-app-layout>