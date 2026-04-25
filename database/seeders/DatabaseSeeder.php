<?php

namespace Database\Seeders;

use App\Models\ServiceRate;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
    }
}
