<div class="rbj-card p-4">
    <h4 class="text-base font-semibold text-sky-900">E-Loyalty Card</h4>
    @php
        $stamps = $stamps ?? 0;
        $filled = min(10, (int) $stamps);
        $rewardAvailable = ($filled >= 10) && empty($rewardRedeemed ?? null);
    @endphp

    <div class="mt-3 flex gap-2 flex-wrap">
        @for ($i = 1; $i <= 10; $i++)
            <div class="w-8 h-8 rounded-full flex items-center justify-center border"
                 style="background: {{ $i <= $filled ? '#0369a1' : '#ffffff' }}; color: {{ $i <= $filled ? '#fff' : '#94a3b8' }};">
                @if ($i <= $filled)
                    ✓
                @endif
            </div>
        @endfor
    </div>

    <p class="mt-3 text-sm text-slate-600">Stamps: <strong>{{ $filled }}</strong>/10</p>
    @if ($rewardAvailable)
        <p class="mt-2 text-sm text-emerald-600">Reward ready: <strong>50% OFF</strong> — redeem at checkout</p>
    @else
        <p class="mt-2 text-sm text-slate-500">Earn 10 stamps to get 50% off your next transaction.</p>
    @endif
</div>
