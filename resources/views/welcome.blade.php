<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>RBJ Laundry Shop</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body style="font-family: Outfit, sans-serif;" class="rbj-water-bg text-slate-800">
        <header x-data="{open:false}" class="sticky top-0 z-30 border-b border-sky-100 bg-white/85 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <img src="{{ asset('asset/logo.jpg') }}" alt="RBJ Laundry Shop" class="h-11 w-11 rounded-2xl border border-sky-200 object-cover shadow-sm" />
                    <div>
                        <p class="text-base font-semibold text-sky-900">RBJ Laundry Shop</p>
                        <p class="text-xs text-sky-700">Clean Made Easy</p>
                    </div>
                </a>

                <div class="hidden items-center gap-3 md:flex">
                    @auth
                        <a href="{{ route('dashboard') }}" class="rbj-btn-outline">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="rbj-btn-outline">Login</a>
                        <a href="{{ route('register') }}" class="rbj-btn-primary">Register</a>
                    @endauth
                </div>

                <button @click="open=!open" class="rounded-2xl border border-sky-200 p-2 text-sky-700 md:hidden" type="button" aria-label="Toggle menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
            <div x-show="open" x-transition class="border-t border-sky-100 bg-white/95 px-4 py-3 md:hidden">
                @auth
                    <a href="{{ route('dashboard') }}" class="block rounded-xl px-3 py-2 text-sky-700 hover:bg-sky-50">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block rounded-xl px-3 py-2 text-sky-700 hover:bg-sky-50">Login</a>
                    <a href="{{ route('register') }}" class="mt-2 block rounded-xl bg-sky-600 px-3 py-2 text-white">Register</a>
                @endauth
            </div>
        </header>

        <main>
            <section class="relative overflow-hidden px-4 pb-16 pt-12 sm:px-6 lg:px-8 lg:pt-18">
                <span class="rbj-bubble h-16 w-16 left-[5%] top-8"></span>
                <span class="rbj-bubble h-10 w-10 left-[45%] top-16" style="animation-delay:1s;"></span>
                <span class="rbj-bubble h-20 w-20 right-[8%] top-10" style="animation-delay:2s;"></span>

                <div class="mx-auto grid max-w-7xl gap-10 lg:grid-cols-2 lg:items-center">
                    <div>
                        <p class="mb-3 inline-flex rounded-full border border-sky-200 bg-white/80 px-3 py-1 text-xs font-medium uppercase tracking-[0.18em] text-sky-700">RBJ Laundry Shop</p>
                        <h1 class="rbj-page-title">Clean Made Easy</h1>
                        <p class="rbj-page-subtitle">Fast, reliable laundry care in Poblacion 1, Victoria, Oriental Mindoro. Bring your load in, or let us handle pickup and delivery for a smoother wash day.</p>
                        <div class="mt-7 flex flex-wrap gap-3">
                            <a href="{{ auth()->check() ? route('bookings.create') : route('register') }}" class="rbj-btn-primary">Book Service</a>
                            <a href="tel:09950155715" class="rbj-btn-outline">Call 0995 015 5715</a>
                            <a href="https://www.facebook.com/RBJLaundryShop" target="_blank" rel="noreferrer" class="rbj-btn-outline">Facebook</a>
                        </div>
                        <div class="mt-7 grid gap-3 sm:grid-cols-3">
                            <div class="rbj-stat">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-600">Open Daily</p>
                                <p class="mt-1 text-lg font-bold text-sky-950">8:00 AM - 5:00 PM</p>
                            </div>
                            <div class="rbj-stat">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-600">Location</p>
                                <p class="mt-1 text-base font-semibold text-sky-950">Poblacion 1</p>
                                <p class="text-sm text-slate-600">Victoria, Oriental Mindoro</p>
                            </div>
                            <div class="rbj-stat">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-600">Contact</p>
                                <p class="mt-1 text-base font-semibold text-sky-950">0995 015 5715</p>
                                <p class="text-sm text-slate-600">RBJ Laundry Shop</p>
                            </div>
                        </div>
                    </div>
                    <div class="rbj-panel p-5 sm:p-7">
                        <img src="{{ Vite::asset('resources/asset/front.jpg') }}" alt="RBJ Laundry Shop" class="mx-auto h-48 w-full rounded-2xl object-cover sm:h-60" />
                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-2xl border border-sky-100 bg-sky-50/80 p-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-sky-600">Service Highlight</p>
                                <p class="mt-1 text-sm font-semibold text-sky-950">Wash • Dry • Fold (Minimum 8 kg)</p>
                                <p class="text-sm text-slate-600">₱199</p>
                            </div>
                            <div class="rounded-2xl border border-sky-100 bg-white/90 p-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-sky-600">Add-On</p>
                                <p class="mt-1 text-sm font-semibold text-sky-950">CONVENIENCE SERVICE (+ ₱20)</p>
                                <p class="text-sm text-slate-600">Pick-Up & Delivery</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="px-4 pb-16 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-7xl">
                    <h2 class="text-center text-2xl font-semibold text-sky-900">Services Offered</h2>
                    <div class="mt-7 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <article class="rbj-panel p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-600">Wash • Dry • Fold</p>
                            <h3 class="mt-2 text-lg font-semibold text-sky-950">Minimum 8 kg</h3>
                            <p class="mt-3 text-3xl font-bold text-sky-700">₱199</p>
                            <p class="mt-2 text-sm text-slate-600">Full service laundry for everyday loads.</p>
                        </article>
                        <article class="rbj-panel p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-600">Wash Only</p>
                            <h3 class="mt-2 text-lg font-semibold text-sky-950">Minimum 8 kg</h3>
                            <p class="mt-3 text-3xl font-bold text-sky-700">₱99</p>
                            <p class="mt-2 text-sm text-slate-600">For customers who only need washing.</p>
                        </article>
                        <article class="rbj-panel p-6 sm:col-span-2 lg:col-span-1">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-600">Dry Only</p>
                            <h3 class="mt-2 text-lg font-semibold text-sky-950">Minimum 8 kg</h3>
                            <p class="mt-3 text-3xl font-bold text-sky-700">₱99</p>
                            <p class="mt-2 text-sm text-slate-600">Quick drying for already washed clothes.</p>
                        </article>
                    </div>
                    <div class="mt-6 grid gap-4 lg:grid-cols-2">
                        <article class="rbj-panel p-6">
                            <h3 class="text-lg font-semibold text-sky-950">Bulky Items</h3>
                            <div class="mt-4 space-y-3 text-sm text-slate-600">
                                <p><span class="font-semibold text-sky-900">Heavy Items</span> (Jackets, blankets, bed covers - Min. 5 kg) - ₱199</p>
                                <p><span class="font-semibold text-sky-900">Comforter</span> (1 pc per load) - ₱199</p>
                            </div>
                        </article>
                        <article class="rbj-panel p-6">
                            <h3 class="text-lg font-semibold text-sky-950">Convenience Services</h3>
                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                <div class="rounded-2xl bg-sky-50 px-4 py-3 text-sm text-slate-700">Pick-Up & Delivery <span class="font-semibold text-sky-700">(+ ₱20)</span></div>
                                <div class="rounded-2xl bg-sky-50 px-4 py-3 text-sm text-slate-700">Fabcon of choice <span class="font-semibold text-sky-700">full service only</span></div>
                                <div class="rounded-2xl bg-sky-50 px-4 py-3 text-sm text-slate-700">Separate washing <span class="font-semibold text-sky-700">upon request</span></div>
                                <div class="rounded-2xl bg-sky-50 px-4 py-3 text-sm text-slate-700">Gentle care for <span class="font-semibold text-sky-700">baby clothes</span></div>
                                <div class="rounded-2xl bg-sky-50 px-4 py-3 text-sm text-slate-700 sm:col-span-2">Same-day service available <span class="font-semibold text-sky-700">*</span></div>
                            </div>
                        </article>
                    </div>
                    <div class="mt-6 rbj-panel p-6">
                        <div class="grid gap-4 md:grid-cols-3 md:items-center">
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-semibold text-sky-950">Contact Information</h3>
                                <p class="mt-2 text-sm text-slate-600">Message or call us for bookings, pickup arrangements, and service questions.</p>
                            </div>
                            <div class="flex flex-wrap gap-3 md:justify-end">
                                <a href="tel:09950155715" class="rbj-btn-primary">Call Now</a>
                                <a href="https://www.facebook.com/RBJLaundryShop" target="_blank" rel="noreferrer" class="rbj-btn-outline">Facebook Page</a>
                            </div>
                        </div>
                        <div class="mt-5 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-2xl border border-sky-100 bg-white/90 p-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-sky-600">Location</p>
                                <p class="mt-1 text-sm font-semibold text-sky-950">Poblacion 1</p>
                                <p class="text-sm text-slate-600">Victoria, Oriental Mindoro</p>
                            </div>
                            <div class="rounded-2xl border border-sky-100 bg-white/90 p-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-sky-600">Facebook</p>
                                <p class="mt-1 text-sm font-semibold text-sky-950">RBJ Laundry Shop</p>
                                <p class="text-sm text-slate-600">Search us on Facebook</p>
                            </div>
                            <div class="rounded-2xl border border-sky-100 bg-white/90 p-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-sky-600">Phone</p>
                                <p class="mt-1 text-sm font-semibold text-sky-950">0995 015 5715</p>
                                <p class="text-sm text-slate-600">Open daily during store hours</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
