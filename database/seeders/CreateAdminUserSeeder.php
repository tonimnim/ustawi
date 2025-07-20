<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists
        if (User::where('email', 'stephenkiarie@ustawiwajamii.org')->exists()) {
            $this->command->info('Admin user already exists.');
            return;
        }

        // Create admin user
        User::create([
            'name' => 'Stephen Kiarie',
            'email' => 'stephenkiarie@ustawiwajamii.org',
            'password' => Hash::make('8800kl.'),
            'role' => UserRole::ADMIN,
            'email_verified_at' => now(),
            'is_active' => true,
            'phone' => null,
            'county' => 'Nairobi',
            'organization' => 'Ustawi Wa Jamii',
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: stephenkiarie@ustawiwajamii.org');
        $this->command->info('Password: 8800kl.');
    }
}