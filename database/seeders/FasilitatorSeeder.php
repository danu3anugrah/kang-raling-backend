<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class FasilitatorSeeder extends Seeder
{
    public function run()
    {
        $users = [
            // Admin
            [
                'name' => env('ADMIN_NAME', 'Administrator'),
                'email' => env('ADMIN_EMAIL', 'admin@kangraling.com'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password123')),
                'role' => 'admin',
                'is_active' => true,
            ],
            // Fasilitator 1
            [
                'name' => env('FASILITATOR1_NAME', 'Fasilitator 1'),
                'email' => env('FASILITATOR1_EMAIL', 'fasilitator@kangraling.com'),
                'password' => Hash::make(env('FASILITATOR1_PASSWORD', 'password123')),
                'role' => 'fasilitator',
                'is_active' => true,
            ],
            // Fasilitator 2
            [
                'name' => env('FASILITATOR2_NAME', 'Fasilitator 2'),
                'email' => env('FASILITATOR2_EMAIL', 'koorfasil@kangraling.com'),
                'password' => Hash::make(env('FASILITATOR2_PASSWORD', 'password123')),
                'role' => 'fasilitator',
                'is_active' => true,
            ],
        ];

        foreach ($users as $userData) {
            $existingUser = User::where('email', $userData['email'])->first();

            if (!$existingUser) {
                User::create($userData);
                echo "✓ User {$userData['email']} ({$userData['role']}) berhasil ditambahkan\n";
            } else {
                // Update existing user
                $existingUser->update($userData);
                echo "✓ User {$userData['email']} ({$userData['role']}) berhasil diupdate\n";
            }
        }
    }
}
