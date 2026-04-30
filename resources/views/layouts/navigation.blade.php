<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-sky-100 bg-white/85 backdrop-blur">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <img src="{{ asset('asset/logo.jpg') }}" alt="RBJ Laundry Shop" class="h-10 w-10 rounded-2xl border border-sky-200 object-cover" />
            <div>
                <p class="text-sm font-semibold text-sky-900">RBJ Laundry Shop</p>
                <p class="text-[11px] text-sky-700">Operations</p>
            </div>
        </a>

        <div class="hidden items-center gap-2 md:flex">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>

            @if (auth()->user()->role === 'customer')
                <x-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">Book Service</x-nav-link>
            @endif

            @if (in_array(auth()->user()->role, ['admin', 'staff'], true))
                <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.index', 'orders.create', 'orders.show', 'orders.edit')">Orders</x-nav-link>
                <x-nav-link :href="route('orders.history')" :active="request()->routeIs('orders.history')">History</x-nav-link>
                <x-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')">Payments</x-nav-link>
                <x-nav-link :href="route('inventories.index')" :active="request()->routeIs('inventories.*')">Inventory</x-nav-link>
                <x-nav-link :href="route('service-rates.index')" :active="request()->routeIs('service-rates.*')">Service Rates</x-nav-link>
                <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">Reports</x-nav-link>
            @endif
        </div>

        <div class="hidden md:flex md:items-center md:gap-3">
            <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-medium text-sky-700 capitalize">{{ auth()->user()->role }}</span>
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="rounded-2xl border border-sky-200 px-3 py-2 text-sm text-slate-700">{{ Auth::user()->name }}</button>
                </x-slot>
                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>

        <button @click="open = !open" class="rounded-2xl border border-sky-200 p-2 text-sky-700 md:hidden" type="button" aria-label="Open menu">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <div x-show="open" x-transition class="border-t border-sky-100 bg-white px-4 pb-4 pt-3 md:hidden">
        <div class="space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-responsive-nav-link>

            @if (auth()->user()->role === 'customer')
                <x-responsive-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">Book Service</x-responsive-nav-link>
            @endif

            @if (in_array(auth()->user()->role, ['admin', 'staff'], true))
                <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.index', 'orders.create', 'orders.show', 'orders.edit')">Orders</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('orders.history')" :active="request()->routeIs('orders.history')">History</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')">Payments</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('inventories.index')" :active="request()->routeIs('inventories.*')">Inventory</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('service-rates.index')" :active="request()->routeIs('service-rates.*')">Service Rates</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">Reports</x-responsive-nav-link>
            @endif
        </div>

        <div class="mt-4 border-t border-sky-100 pt-3">
            <div class="text-sm font-semibold text-slate-700">{{ Auth::user()->name }}</div>
            <div class="text-xs text-slate-500">{{ Auth::user()->email }}</div>
            <div class="mt-2 rounded-full bg-sky-100 px-3 py-1 text-xs font-medium text-sky-700 inline-block capitalize">{{ auth()->user()->role }}</div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">Profile</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
