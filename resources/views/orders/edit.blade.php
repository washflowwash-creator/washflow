<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-sky-900">Edit Order #{{ $order->id }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <p class="rbj-card p-6 text-slate-700">Use the order details page to update status and pricing.</p>
        </div>
    </div>
</x-app-layout>
