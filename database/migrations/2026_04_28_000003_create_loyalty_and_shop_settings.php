<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoyaltyAndShopSettings extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loyalty_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedSmallInteger('stamps')->default(0);
            $table->timestamp('first_stamp_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('reward_generated')->default(false);
            $table->timestamp('reward_redeemed_at')->nullable();
            $table->string('reward_code')->nullable();
            $table->timestamps();
        });

        Schema::create('shop_settings', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['OPEN', 'CLOSED', 'TEMPORARILY_UNAVAILABLE'])->default('OPEN');
            $table->unsignedInteger('capacity')->default(30); // total concurrent loads
            $table->unsignedInteger('processing_capacity')->default(5); // loads processed per day
            $table->unsignedInteger('current_active_loads')->default(0);
            $table->unsignedTinyInteger('nearly_full_threshold')->default(80); // percent
            $table->timestamps();
        });

        // Extend orders table with scheduling and completion timestamps
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'scheduled_at')) {
                    $table->timestamp('scheduled_at')->nullable()->after('created_at');
                }
                if (!Schema::hasColumn('orders', 'estimated_completed_at')) {
                    $table->timestamp('estimated_completed_at')->nullable()->after('scheduled_at');
                }
                if (!Schema::hasColumn('orders', 'completed_at')) {
                    $table->timestamp('completed_at')->nullable()->after('estimated_completed_at');
                }
                if (!Schema::hasColumn('orders', 'order_number')) {
                    $table->string('order_number')->nullable()->unique()->after('id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'scheduled_at')) {
                    $table->dropColumn('scheduled_at');
                }
                if (Schema::hasColumn('orders', 'estimated_completed_at')) {
                    $table->dropColumn('estimated_completed_at');
                }
                if (Schema::hasColumn('orders', 'completed_at')) {
                    $table->dropColumn('completed_at');
                }
                if (Schema::hasColumn('orders', 'order_number')) {
                    $table->dropColumn('order_number');
                }
            });
        }

        Schema::dropIfExists('shop_settings');
        Schema::dropIfExists('loyalty_cards');
    }
}
