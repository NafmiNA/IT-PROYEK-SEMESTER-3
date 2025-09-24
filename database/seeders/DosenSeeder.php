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
