<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountAndRewardToPayments extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (!Schema::hasColumn('payments', 'discount_amount')) {
                    $table->decimal('discount_amount', 10, 2)->default(0)->after('amount');
                }
                if (!Schema::hasColumn('payments', 'reward_redeemed')) {
                    $table->boolean('reward_redeemed')->default(false)->after('discount_amount');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (Schema::hasColumn('payments', 'discount_amount')) {
                    $table->dropColumn('discount_amount');
                }
                if (Schema::hasColumn('payments', 'reward_redeemed')) {
                    $table->dropColumn('reward_redeemed');
                }
            });
        }
    }
}
