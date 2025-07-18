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
        // No admin users will be automatically seeded
        // Admin users should be created through the application interface
        // or manually through proper user registration/creation process
        
        $this->command->info('Admin seeder disabled. Create admin users through the application interface.');
    }
}