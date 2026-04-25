<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-xl font-semibold text-sky-900">Payments</h2>
            <a href="{{ route('payments.create') }}" class="rbj-btn-primary">Record Payment</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-2xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-800">{{ session('success') }}</div>
            @endif
            <div class="rbj-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-sky-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-3">Order</th>
                                <th class="px-4 py-3">Method</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Amount</th>
                                <th class="px-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                <tr class="border-t border-sky-100">
                                    <td class="px-4 py-3">#{{ $payment->order_id }}</td>
                                    <td class="px-4 py-3">{{ $payment->payment_method }}</td>
                                    <td class="px-4 py-3"><span class="{{ $payment->payment_status === 'paid' ? 'rbj-badge rbj-badge-completed' : 'rbj-badge rbj-badge-pending' }}">{{ $payment->payment_status }}</span></td>
                                    <td class="px-4 py-3">PHP {{ number_format($payment->amount, 2) }}</td>
                                    <td class="px-4 py-3"><a class="rbj-btn-outline !px-3 !py-1.5" href="{{ route('payments.show', $payment) }}">View</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4">{{ $payments->links() }}</div>
        </div>
    </div>
</x-app-layout>
