<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dosen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDosenSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'andi@kampus.ac.id'],
            ['name' => 'Dr. Andi', 'password' => Hash::make('password'), 'role' => 'dosen']
        );

        Dosen::firstOrCreate(
            ['user_id' => $user->id],
            ['nidn' => '0012345678', 'nama' => 'Dr. Andi', 'email' => 'andi@kampus.ac.id', 'status_aktif' => true]
        );
    }
}
