<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-sky-900">{{ $user->name }}'s Loyalty Card</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <a href="{{ route('admin.loyalty.index') }}" class="text-sky-600 hover:underline mb-4 inline-block">← Back to Monitoring</a>

            <div class="grid gap-6 lg:grid-cols-3 mb-6">
                <div class="rbj-card p-5">
                    <h4 class="text-sm font-semibold text-slate-600 uppercase">Customer Info</h4>
                    <p class="mt-2 font-semibold text-sky-900">{{ $user->name }}</p>
                    <p class="text-sm text-slate-600">{{ $user->email }}</p>
                    <p class="mt-2 text-xs text-slate-500">Joined: {{ $user->created_at->format('M d, Y') }}</p>
                </div>

                <div class="rbj-card p-5">
                    <h4 class="text-sm font-semibold text-slate-600 uppercase">Stamp Progress</h4>
                    <p class="mt-3 text-3xl font-bold text-sky-900">{{ $loyalty->stamps ?? 0 }}<span class="text-lg text-slate-400">/10</span></p>
                    <div class="mt-3 flex gap-1 flex-wrap">
                        @for($i = 1; $i <= 10; $i++)
                            <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center text-xs font-bold"
                                 style="background: {{ ($i <= ($loyalty->stamps ?? 0)) ? '#0369a1' : '#ffffff' }}; border-color: {{ ($i <= ($loyalty->stamps ?? 0)) ? '#0369a1' : '#cbd5e1' }}; color: {{ ($i <= ($loyalty->stamps ?? 0)) ? '#ffffff' : '#94a3b8' }};">
                                @if($i <= ($loyalty->stamps ?? 0))✓@endif
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="rbj-card p-5">
                    <h4 class="text-sm font-semibold text-slate-600 uppercase">Reward Status</h4>
                    @if($loyalty && $loyalty->reward_generated && !$loyalty->reward_redeemed_at)
                        <div class="mt-2 p-3 rounded-lg bg-emerald-50 border border-emerald-200">
                            <p class="text-xs text-slate-600">AVAILABLE FOR REDEMPTION</p>
                            <p class="text-lg font-bold text-emerald-700">50% OFF</p>
                            <p class="text-xs text-slate-600 mt-1">Code: {{ $loyalty->reward_code }}</p>
                        </div>
                    @elseif($loyalty && $loyalty->reward_redeemed_at)
                        <div class="mt-2 p-3 rounded-lg bg-slate-50 border border-slate-200">
                            <p class="text-xs text-slate-600">REDEEMED ON</p>
                            <p class="font-semibold text-sky-900">{{ $loyalty->reward_redeemed_at->format('M d, Y') }}</p>
                        </div>
                    @else
                        <div class="mt-2 p-3 rounded-lg bg-slate-50 border border-slate-200">
                            <p class="text-xs text-slate-600">Complete 10 stamps to earn 50% OFF</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="rbj-card p-5">
                <h4 class="font-semibold text-sky-900 mb-3">Completed Transactions</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-sky-50 border-b border-sky-200">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold text-sky-900">Date</th>
                                <th class="px-3 py-2 text-left font-semibold text-sky-900">Service</th>
                                <th class="px-3 py-2 text-center font-semibold text-sky-900">Amount</th>
                                <th class="px-3 py-2 text-center font-semibold text-sky-900">Payment</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-sky-100">
                            @forelse($orders as $order)
                                <tr class="hover:bg-sky-50">
                                    <td class="px-3 py-2 text-slate-600">{{ $order->completed_at?->format('M d, Y h:i A') ?? $order->updated_at->format('M d, Y') }}</td>
                                    <td class="px-3 py-2 font-medium">{{ $order->booking->service_type }}</td>
                                    <td class="px-3 py-2 text-center">PHP {{ number_format($order->total_cost, 2) }}</td>
                                    <td class="px-3 py-2 text-center">
                                        <span class="text-xs px-2 py-1 rounded-full"
                                              style="background: {{ $order->payment?->payment_status === 'paid' ? '#d1fae5' : '#fee2e2' }}; color: {{ $order->payment?->payment_status === 'paid' ? '#065f46' : '#991b1b' }};">
                                            {{ $order->payment?->payment_status ?? 'unpaid' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3 py-2 text-center text-slate-500 text-sm">No completed orders</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
