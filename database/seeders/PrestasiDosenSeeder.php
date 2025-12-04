<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\PrestasiDosen;
use Illuminate\Database\Seeder;

class PrestasiDosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dosens = Dosen::all();
        
        if ($dosens->isEmpty()) {
            $this->command->error('No dosens found. Please seed dosens first.');
            return;
        }
        
        $tahun = now()->year;
        
        // Sample prestasi data untuk testing SAW
        $prestasiData = [
            [
                'publikasi' => 15,
                'skor_sinta' => 250,
                'hibah' => 50000000, // 50 juta
                'buku' => 3,
            ],
            [
                'publikasi' => 10,
                'skor_sinta' => 180,
                'hibah' => 30000000, // 30 juta
                'buku' => 2,
            ],
            [
                'publikasi' => 20,
                'skor_sinta' => 300,
                'hibah' => 75000000, // 75 juta
                'buku' => 5,
            ],
            [
                'publikasi' => 8,
                'skor_sinta' => 150,
                'hibah' => 20000000, // 20 juta
                'buku' => 1,
            ],
            [
                'publikasi' => 12,
                'skor_sinta' => 200,
                'hibah' => 40000000, // 40 juta
                'buku' => 2,
            ],
        ];
        
        foreach ($dosens->take(5) as $index => $dosen) {
            if (isset($prestasiData[$index])) {
                PrestasiDosen::updateOrCreate(
                    [
                        'dosen_id' => $dosen->id,
                        'tahun' => $tahun,
                    ],
                    $prestasiData[$index]
                );
                
                $this->command->info("Created prestasi for: {$dosen->nama}");
            }
        }
        
        $this->command->info('Prestasi dosen seeded successfully!');
    }
}
