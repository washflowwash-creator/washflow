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
    <body class="antialiased" style="font-family: Outfit, sans-serif;">
        <div class="relative min-h-screen overflow-hidden rbj-water-bg">
            <div class="pointer-events-none absolute left-0 top-0 h-72 w-72 -translate-x-1/2 -translate-y-1/2 rounded-full bg-sky-300/20 blur-3xl"></div>
            <div class="pointer-events-none absolute right-0 top-24 h-80 w-80 translate-x-1/3 rounded-full bg-cyan-300/20 blur-3xl"></div>
            <div class="pointer-events-none absolute bottom-0 left-1/2 h-80 w-80 -translate-x-1/2 translate-y-1/2 rounded-full bg-blue-300/15 blur-3xl"></div>
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="rbj-glass shadow-sm">
                    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="relative z-10">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
