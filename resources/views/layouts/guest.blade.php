<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="text-slate-900 antialiased" style="font-family: Outfit, sans-serif;">
        <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-blue-700 via-sky-600 to-cyan-400 px-4 py-8 sm:flex sm:items-center sm:justify-center">
            <div class="pointer-events-none absolute left-0 top-0 h-80 w-80 rounded-full bg-white/15 blur-3xl"></div>
            <div class="pointer-events-none absolute right-0 top-1/2 h-96 w-96 rounded-full bg-cyan-200/20 blur-3xl"></div>
            <span class="rbj-bubble h-14 w-14 left-10 top-16"></span>
            <span class="rbj-bubble h-10 w-10 right-14 top-28" style="animation-delay: 1s;"></span>
            <span class="rbj-bubble h-20 w-20 right-8 bottom-20" style="animation-delay: 2s;"></span>

            <div class="relative z-10 w-full max-w-md">
                <a href="/" class="mb-5 flex items-center justify-center gap-3 text-white">
                    <img src="{{ Vite::asset('resources/asset/logo.jpg') }}" alt="RBJ Laundry Shop Logo" class="h-16 w-16 rounded-2xl border border-white/50 object-cover shadow-lg" />
                    <div>
                        <p class="text-lg font-semibold tracking-wide">RBJ Laundry Shop</p>
                        <p class="text-xs text-white/85">Clean Made Easy</p>
                    </div>
                </a>

                <div class="rbj-panel w-full px-6 py-6 sm:px-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
