<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class FasilitatorSeeder extends Seeder
{
    public function run()
    {
        // Check if environment is production
        if (app()->environment('production')) {
            $this->command->info('Seeder tidak dijalankan di production environment.');
            return;
        }

        $users = [
            [
                'name' => env('ADMIN_NAME'),
                'email' => env('ADMIN_EMAIL'),
                'password' => Hash::make(env('ADMIN_PASSWORD')),
                'role' => 'admin',
                'is_active' => true,
            ]
        ];

        foreach ($users as $userData) {
            if (empty($userData['name']) || empty($userData['email']) || empty(env('ADMIN_PASSWORD'))) {
                $this->command->error('❌ Environment variables untuk admin user tidak ditemukan.');
                continue;
            }

            $existingUser = User::where('email', $userData['email'])->first();

            if (!$existingUser) {
                User::create($userData);
                $this->command->info("✓ User admin {$userData['email']} berhasil ditambahkan");
            } else {
                $this->command->info("✓ User admin {$userData['email']} sudah ada");
            }
        }
    }
}
