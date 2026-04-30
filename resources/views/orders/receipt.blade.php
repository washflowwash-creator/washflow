<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $order->id }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-sky-50 p-6" onload="window.print()">
    <div class="mx-auto max-w-2xl rbj-card p-6">
        <div class="flex items-center justify-between border-b border-sky-100 pb-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('asset/logo.jpg') }}" alt="RBJ Laundry Shop" class="h-14 w-14 rounded-2xl border border-sky-200 object-cover">
                <div>
                    <h1 class="text-lg font-bold text-sky-900">RBJ Laundry Shop</h1>
                    <p class="text-sm text-slate-600">Official Receipt</p>
                </div>
            </div>
            <div class="text-right text-sm text-slate-600">
                <p>Receipt #: {{ $order->id }}</p>
                <p>Date: {{ now()->format('M d, Y h:i A') }}</p>
            </div>
        </div>

        <div class="mt-5 space-y-2 text-sm text-slate-700">
            <p><span class="font-semibold">Customer:</span> {{ $order->booking->user->name ?? '-' }}</p>
            <p><span class="font-semibold">Service:</span> {{ $order->booking->service_type ?? '-' }}</p>
            <p><span class="font-semibold">Status:</span> {{ $order->status }}</p>
            <p><span class="font-semibold">Weight:</span> {{ $order->weight_kg }} kg</p>
            @php
                $serviceType = $order->booking->service_type ?? '';
                $adminPrice = \App\Models\ServiceRate::query()->where('service_type', $serviceType)->value('price_per_kg');
                preg_match('/(\d+)\s*kg/i', $serviceType, $m);
                $minKg = $m[1] ?? null;
            @endphp
            <p>
                <span class="font-semibold">Rate:</span>
                @if($minKg && $adminPrice)
                    PHP {{ number_format((float) $adminPrice, 2) }} (pack price for {{ $minKg }} kg) — effective PHP {{ number_format($order->unit_price, 2) }} / kg
                @else
                    PHP {{ number_format($order->unit_price, 2) }} / kg
                @endif
            </p>
            <p><span class="font-semibold">Payment Method:</span> {{ $order->payment?->payment_method ?? 'N/A' }}</p>
            <p><span class="font-semibold">Payment Status:</span> {{ $order->payment?->payment_status ?? 'unpaid' }}</p>
        </div>

        <div class="mt-6 rounded-2xl bg-sky-50 p-4 text-right">
            <p class="text-sm text-slate-600">Total</p>
            <p class="text-2xl font-bold text-sky-900">PHP {{ number_format($order->total_cost, 2) }}</p>
        </div>
    </div>
</body>
</html>
