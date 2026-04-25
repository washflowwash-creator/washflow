<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-sky-900">Edit Payment #{{ $payment->id }}</h2></x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('payments.update', $payment) }}" class="rbj-card space-y-4 p-6">
                @csrf
                @method('PUT')
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-slate-700">Method</label>
                        <select name="payment_method" class="rbj-input">
                            <option value="cash" @selected($payment->payment_method === 'cash')>Cash</option>
                            <option value="gcash/manual" @selected($payment->payment_method === 'gcash/manual')>GCash/Manual</option>
                        </select>
                    </div>
                    <div class="rounded-2xl border border-sky-100 bg-sky-50 p-3">
                        <p class="text-xs text-slate-600">Order Total</p>
                        <p class="text-lg font-semibold text-sky-900">PHP {{ number_format($payment->order->total_cost, 2) }}</p>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Amount</label>
                    <input id="amount" type="number" step="0.01" min="0" name="amount" value="{{ $payment->amount }}" class="rbj-input">
                    <label class="mt-2 inline-flex items-center gap-2 text-sm text-slate-700">
                        <input id="is_full_payment" type="checkbox" name="is_full_payment" value="1" class="rounded border-sky-300 text-sky-600 focus:ring-sky-500" @checked((float) $payment->amount >= (float) $payment->order->total_cost)>
                        Full payment
                    </label>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Reference</label>
                    <input name="reference" value="{{ $payment->reference }}" class="rbj-input">
                </div>
                <div class="flex gap-2">
                    <button class="rbj-btn-primary" type="submit">Update Payment</button>
                    <a href="{{ route('payments.show', $payment) }}" class="rbj-btn-outline inline-flex">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const amountInput = document.getElementById('amount');
            const fullPaymentInput = document.getElementById('is_full_payment');
            const total = Number({{ (float) $payment->order->total_cost }});

            function sync() {
                if (fullPaymentInput.checked) {
                    amountInput.value = total.toFixed(2);
                }
            }

            fullPaymentInput.addEventListener('change', sync);
            sync();
        });
    </script>
</x-app-layout>
