<?php

namespace Database\Seeders;

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
        User::query()->updateOrCreate(['email' => 'admin@example.com'], [
            'first_name' => 'Demo',
            'last_name' => 'Admin',
            'name' => 'Demo Admin',
            'phone_number' => '+1 555 0100',
            'password' => Hash::make('Password@123'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        if (User::query()->count() < 20) {
            User::factory(35)->create();
            User::factory(5)->inactive()->create();
        }
    }
}
