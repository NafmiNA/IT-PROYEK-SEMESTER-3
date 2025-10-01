<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDosenSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'andi@kampus.ac.id'],
            [
                'name' => 'Andi Dosen',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
            ]
        );

        // buat baris dosen terhubung ke user
        DB::table('dosens')->updateOrInsert(
            ['email' => 'andi@kampus.ac.id'],
            [
                'nama' => 'Andi Dosen',
                'nidn' => '0012345678',
                'jabatan_fungsional' => 'Lektor',
                'status_aktif' => true,
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
