<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="flex min-h-screen flex-col items-center justify-center bg-gradient-to-b from-brand-50 via-gray-50 to-white px-4 py-10">
            <a href="{{ route('home') }}" class="mb-8 flex flex-col items-center gap-2" aria-label="Home">
                <x-application-logo class="h-14 w-14 fill-current text-brand-600" />
                <span class="text-lg font-semibold tracking-tight text-gray-900">
                    {{ config('app.name', 'Laravel') }}
                </span>
            </a>

            <div class="w-full max-w-md rounded-2xl border border-gray-100 bg-white p-6 shadow-xl shadow-gray-200/50 sm:p-8">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
