<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user for development
        if (app()->environment('local', 'development')) {
            $admin = User::firstOrCreate(
                ['email' => 'admin@ustawiwajamii.org'],
                [
                    'name' => 'Admin User',
                    'password' => Hash::make('password123'),
                    'role' => UserRole::ADMIN,
                    'email_verified_at' => now(),
                    'is_active' => true,
                ]
            );
            
            $this->command->info('Admin user created: admin@ustawiwajamii.org / password123');
        } else {
            $this->command->warn('Admin seeder disabled in production. Create admin users through the application interface.');
        }
    }
}