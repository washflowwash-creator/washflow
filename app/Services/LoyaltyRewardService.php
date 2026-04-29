<?php

namespace App\Services;

use App\Models\User;
use App\Models\LoyaltyCard;
use Carbon\Carbon;

class LoyaltyRewardService
{
    /**
     * Award one stamp for a completed order and generate reward if 10 stamps reached
     */
    public function awardStamp(User $user): void
    {
        $loyalty = $user->loyaltyCard()->firstOrCreate(['user_id' => $user->id]);

        // set first_stamp_at if this is the first stamp
        if ($loyalty->stamps === 0) {
            $loyalty->first_stamp_at = now();
            $loyalty->expires_at = now()->addYear();
        }

        $loyalty->stamps += 1;

        // generate reward at 10 stamps
        if ($loyalty->stamps >= 10 && ! $loyalty->reward_generated) {
            $loyalty->reward_generated = true;
            $loyalty->reward_code = 'LOYALTY50'.str_pad($user->id, 6, '0', STR_PAD_LEFT).Carbon::now()->format('Ymd');
        }

        $loyalty->save();
    }

    /**
     * Redeem the generated reward (50% OFF)
     */
    public function redeemReward(User $user): void
    {
        $loyalty = $user->loyaltyCard()->firstOrCreate(['user_id' => $user->id]);

        if ($loyalty->reward_generated && ! $loyalty->reward_redeemed_at) {
            $loyalty->reward_redeemed_at = now();
            $loyalty->save();

            // reset for new cycle
            $loyalty->stamps = 0;
            $loyalty->reward_generated = false;
            $loyalty->reward_code = null;
            $loyalty->first_stamp_at = null;
            $loyalty->expires_at = null;
            $loyalty->save();
        }
    }

    /**
     * Check if reward is available for redemption
     */
    public function hasAvailableReward(User $user): bool
    {
        $loyalty = $user->loyaltyCard()->first();

        return $loyalty && $loyalty->reward_generated && ! $loyalty->reward_redeemed_at;
    }
}
