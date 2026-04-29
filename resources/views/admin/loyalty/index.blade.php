<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-sky-900">Loyalty Card Monitoring</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="mb-4 flex items-center gap-3">
                <form method="GET" action="{{ route('admin.loyalty.index') }}" class="flex gap-2 flex-1">
                    <input type="text" name="search" placeholder="Search by name or email" value="{{ request('search') }}" class="rbj-input flex-1">
                    <button type="submit" class="rbj-btn-primary">Search</button>
                </form>
            </div>

            <div class="rbj-card overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-sky-50 border-b border-sky-200">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-sky-900">Customer</th>
                            <th class="px-4 py-3 text-left font-semibold text-sky-900">Email</th>
                            <th class="px-4 py-3 text-center font-semibold text-sky-900">Stamps</th>
                            <th class="px-4 py-3 text-center font-semibold text-sky-900">Reward</th>
                            <th class="px-4 py-3 text-center font-semibold text-sky-900">Valid Until</th>
                            <th class="px-4 py-3 text-right font-semibold text-sky-900">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sky-100">
                        @forelse($loyalties as $loyalty)
                            <tr class="hover:bg-sky-50 transition">
                                <td class="px-4 py-3 font-medium text-sky-900">{{ $loyalty->user->name }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $loyalty->user->email }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 rounded-full bg-sky-100 text-sky-700 font-semibold">
                                        {{ $loyalty->stamps }}/10
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($loyalty->reward_generated && !$loyalty->reward_redeemed_at)
                                        <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 font-semibold text-xs">50% OFF Available</span>
                                    @elseif($loyalty->reward_redeemed_at)
                                        <span class="inline-block px-3 py-1 rounded-full bg-slate-100 text-slate-700 font-semibold text-xs">Redeemed</span>
                                    @else
                                        <span class="text-xs text-slate-500">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center text-xs text-slate-600">
                                    {{ $loyalty->expires_at?->format('M d, Y') ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.loyalty.show', $loyalty->user) }}" class="text-sky-600 hover:text-sky-900 underline">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center text-slate-500">No loyalty cards found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $loyalties->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
