<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-sky-900">Record Payment</h2></x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('payments.store') }}" class="rbj-card space-y-4 p-6">
                @csrf
                <div>
                    <label class="text-sm font-medium text-slate-700">Order</label>
                    <select id="order_id" name="order_id" required class="rbj-input">
                        <option value="">Select order</option>
                        @foreach ($orders as $order)
                            <option value="{{ $order->id }}" data-total="{{ (float) $order->total_cost }}" @selected(old('order_id') == $order->id)>#{{ $order->id }} - {{ $order->booking->service_type }} - PHP {{ number_format($order->total_cost, 2) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-slate-700">Method</label>
                        <select name="payment_method" class="rbj-input">
                            <option value="cash">Cash</option>
                            <option value="gcash/manual">GCash/Manual</option>
                        </select>
                    </div>
                    <div class="rounded-2xl border border-sky-100 bg-sky-50 p-3">
                        <p class="text-xs text-slate-600">Order Total</p>
                        <p id="order_total_preview" class="text-lg font-semibold text-sky-900">PHP 0.00</p>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Amount</label>
                    <input id="amount" type="number" step="0.01" min="0" name="amount" required class="rbj-input">
                    <label class="mt-2 inline-flex items-center gap-2 text-sm text-slate-700">
                        <input id="is_full_payment" type="checkbox" name="is_full_payment" value="1" class="rounded border-sky-300 text-sky-600 focus:ring-sky-500" checked>
                        Full payment
                    </label>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Reference</label>
                    <input name="reference" class="rbj-input" placeholder="Optional">
                </div>
                <div class="flex gap-2">
                    <button class="rbj-btn-primary" type="submit">Save Payment</button>
                    <a href="{{ route('payments.index') }}" class="rbj-btn-outline inline-flex">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const orderSelect = document.getElementById('order_id');
            const amountInput = document.getElementById('amount');
            const fullPaymentInput = document.getElementById('is_full_payment');
            const preview = document.getElementById('order_total_preview');

            function selectedTotal() {
                const selected = orderSelect.options[orderSelect.selectedIndex];
                if (!selected) {
                    return 0;
                }
                return Number(selected.getAttribute('data-total') || 0);
            }

            function sync() {
                const total = selectedTotal();
                preview.textContent = `PHP ${total.toFixed(2)}`;
                if (fullPaymentInput.checked) {
                    amountInput.value = total.toFixed(2);
                }
            }

            fullPaymentInput.addEventListener('change', sync);
            orderSelect.addEventListener('change', sync);
            sync();
        });
    </script>
</x-app-layout>
