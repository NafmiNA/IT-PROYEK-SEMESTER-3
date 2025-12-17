<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Dosen;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstWhere('email', 'andi@kampus.ac.id');

        // Update role user menjadi dosen jika belum
        if ($user && $user->role !== 'dosen') {
            $user->update(['role' => 'dosen']);
        }

        // kalau user berhasil dibuat
        if ($user) {
            Dosen::updateOrCreate(
                ['nidn' => '1234567890'],
                [
                    'nama'         => 'Andi Dosen',
                    'email'        => 'andi@kampus.ac.id',
                    'status_aktif' => true,
                    'user_id'      => $user->id,
                ]
            );
        }
    }
}
