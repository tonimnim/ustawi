<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Admin users to create
        $admins = [
            [
                'email' => 'stephenkiarie@ustawiwajamii.org',
                'name' => 'Stephen Kiarie',
            ],
            [
                'email' => 'tumainikimathi@ustawiwajamii.org',
                'name' => 'Tumaini Kimathi',
            ],
            [
                'email' => 'info@ustawiwajamii.org',
                'name' => 'Ustawi Admin',
            ],
        ];

        foreach ($admins as $admin) {
            // Check if user doesn't exist before creating
            if (!DB::table('users')->where('email', $admin['email'])->exists()) {
                DB::table('users')->insert([
                    'name' => $admin['name'],
                    'email' => $admin['email'],
                    'password' => Hash::make('Playball22!'),
                    'role' => 'admin',
                    'email_verified_at' => now(),
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally remove the admin users
        DB::table('users')->whereIn('email', [
            'stephenkiarie@ustawiwajamii.org',
            'tumainikimathi@ustawiwajamii.org',
            'info@ustawiwajamii.org',
        ])->delete();
    }
};