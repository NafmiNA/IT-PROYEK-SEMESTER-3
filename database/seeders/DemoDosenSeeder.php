<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DemoDosenSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'andi@kampus.ac.id'], // key unik
            [
                'name'              => 'Andi Dosen',
                'password'          => Hash::make('password123'),
                'role'              => 'dosen',       // jika ada
                'email_verified_at' => now(),         // opsional
            ]
        );
    }
}

