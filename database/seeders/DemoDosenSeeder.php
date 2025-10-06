<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDosenSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'andi@kampus.ac.id'],
            [
                'name'              => 'Andi Dosen',
                'password'          => Hash::make('password123'),
                'role'              => 'dosen',
                'email_verified_at' => now(),
            ]
        );
    }
}
