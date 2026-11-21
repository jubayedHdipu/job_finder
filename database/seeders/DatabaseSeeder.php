<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create or update Admin User
        User::firstOrCreate(
            ['email' => 'admin@bdjobfinder.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create or update Regular Job Seeker User
        User::firstOrCreate(
            ['email' => 'user@bdjobfinder.com'],
            [
                'name' => 'Job Seeker',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        // Seed 25 realistic Bangladeshi jobs
        $this->call(JobSeeder::class);
    }
}
