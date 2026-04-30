<?php

namespace Database\Seeders;

use App\Models\ServiceRate;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@washflow.test'],
            [
                'name' => 'WashFlow Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

         User::query()->updateOrCreate(
            ['email' => 'admins@washflow.test'],
            [
                'name' => 'WashFlow Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'staff@washflow.test'],
            [
                'name' => 'WashFlow Staff',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'email_verified_at' => now(),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'customer@washflow.test'],
            [
                'name' => 'WashFlow Customer',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'email_verified_at' => now(),
            ]
        );

        ServiceRate::query()->updateOrCreate(
            ['service_type' => 'Wash • Dry • Fold (Minimum 8 kg)'],
            ['price_per_kg' => 199]
        );

        ServiceRate::query()->updateOrCreate(
            ['service_type' => 'Wash Only (Minimum 8 kg)'],
            ['price_per_kg' => 99]
        );

        ServiceRate::query()->updateOrCreate(
            ['service_type' => 'Dry Only (Minimum 8 kg)'],
            ['price_per_kg' => 99]
        );

        ServiceRate::query()->updateOrCreate(
            ['service_type' => 'Heavy Items (Min. 5 kg)'],
            ['price_per_kg' => 199]
        );

        ServiceRate::query()->updateOrCreate(
            ['service_type' => 'Comforter (1 pc per load)'],
            ['price_per_kg' => 199]
        );

        // Seed default shop settings
        DB::table('shop_settings')->updateOrInsert(
            ['id' => 1],
            [
                'status' => 'OPEN',
                'capacity' => 30,
                'processing_capacity' => 5,
                'current_active_loads' => 0,
                'nearly_full_threshold' => 80,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Seed a sample loyalty card for the demo customer
        $customer = User::where('email', 'customer@washflow.test')->first();
        if ($customer) {
            DB::table('loyalty_cards')->updateOrInsert(
                ['user_id' => $customer->id],
                [
                    'stamps' => 2,
                    'first_stamp_at' => now()->subMonths(2),
                    'expires_at' => now()->addYear(),
                    'reward_generated' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
