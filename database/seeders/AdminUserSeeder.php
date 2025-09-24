<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // buat/ubah user dengan email ini
        User::updateOrCreate(
            ['email' => 'andi@kampus.ac.id'],
            [
                'name' => 'Andi',
                'password' => Hash::make('password123'), // ganti sesuai keinginan
            ]
        );
    }
}

