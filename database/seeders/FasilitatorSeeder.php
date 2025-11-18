<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class FasilitatorSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Fasilitator Kang Raling',
            'email' => 'fasilitator@kangraling.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
