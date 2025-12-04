<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\PrestasiDosen;
use App\Models\User;
use Illuminate\Database\Seeder;

class PrestasiDosenRealDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahun = now()->year;
        
        // Data real dari contoh yang diberikan
        $prestasiData = [
            ['nama' => 'Afian Syafaadi Rizki, M.Kom', 'publikasi' => 0, 'hibah' => 0, 'skor_sinta' => 7, 'buku' => 0],
            ['nama' => 'Ir. Agustian Noor, M.Kom', 'publikasi' => 0, 'hibah' => 69367000, 'skor_sinta' => 274, 'buku' => 5],
            ['nama' => 'Aidil Fajar Zulfahri, S.Kom., M.Kom', 'publikasi' => 0, 'hibah' => 7500000, 'skor_sinta' => 47, 'buku' => 0],
            ['nama' => 'Billy Sabella, S.Kom., M.Kom', 'publikasi' => 0, 'hibah' => 29253000, 'skor_sinta' => 42, 'buku' => 0],
            ['nama' => 'Cahya Karima, M.Kom', 'publikasi' => 0, 'hibah' => 0, 'skor_sinta' => 4, 'buku' => 0],
            ['nama' => 'Dwi Agung Wibowo, M.Kom', 'publikasi' => 0, 'hibah' => 18050000, 'skor_sinta' => 26, 'buku' => 0],
            ['nama' => 'Herfia Rhomadhona, S.Kom., M.Cs', 'publikasi' => 1, 'hibah' => 82309000, 'skor_sinta' => 190, 'buku' => 3],
            ['nama' => 'Jaka Permadi, S.Si., M.Cs', 'publikasi' => 2, 'hibah' => 66996000, 'skor_sinta' => 167, 'buku' => 1],
            ['nama' => 'Khairul Anwar Hafidz, M.Kom', 'publikasi' => 0, 'hibah' => 82830000, 'skor_sinta' => 541, 'buku' => 9],
            ['nama' => 'Mamed Rofendi Manalu, M.Kom', 'publikasi' => 1, 'hibah' => 19849000, 'skor_sinta' => 77, 'buku' => 1],
            ['nama' => 'Nina Mia Aristi, M.Kom', 'publikasi' => 0, 'hibah' => 77500000, 'skor_sinta' => 48, 'buku' => 1],
            ['nama' => 'Oky Rahmanto, S.Kom., M.T', 'publikasi' => 2, 'hibah' => 45000000, 'skor_sinta' => 99, 'buku' => 1],
            ['nama' => 'Rabini Sayyidati, M.Pd', 'publikasi' => 0, 'hibah' => 42823000, 'skor_sinta' => 10, 'buku' => 0],
            ['nama' => 'Sausan Hidayah Nova, S.Kom., M.Kom', 'publikasi' => 0, 'hibah' => 0, 'skor_sinta' => 18, 'buku' => 0],
            ['nama' => 'Veri Julianto, M.Si', 'publikasi' => 1, 'hibah' => 188514000, 'skor_sinta' => 300, 'buku' => 3],
            ['nama' => 'Winda Aprianti, M.Si', 'publikasi' => 4, 'hibah' => 95853000, 'skor_sinta' => 332, 'buku' => 4],
            ['nama' => 'Wiwik Kusrini, S.Kom., M.Cs', 'publikasi' => 0, 'hibah' => 15753000, 'skor_sinta' => 33, 'buku' => 0],
            ['nama' => 'Yunita Prastyaningsih, M.Kom', 'publikasi' => 0, 'hibah' => 10000000, 'skor_sinta' => 6, 'buku' => 0],
            ['nama' => 'Zaenul Mutaqin, M.M.S.I', 'publikasi' => 0, 'hibah' => 0, 'skor_sinta' => 9, 'buku' => 0],
            ['nama' => 'Nindy Permatasari, S.Kom., M.Kom', 'publikasi' => 0, 'hibah' => 0, 'skor_sinta' => 4, 'buku' => 0],
        ];
        
        $this->command->info('Seeding prestasi data for ' . count($prestasiData) . ' dosen...');
        
        // Get existing dosens
        $existingDosens = Dosen::orderBy('id')->get();
        
        if ($existingDosens->count() < count($prestasiData)) {
            $this->command->warn('Warning: Only ' . $existingDosens->count() . ' dosens found in database.');
            $this->command->info('Will map data to existing dosens...');
        }
        
        foreach ($prestasiData as $index => $data) {
            // Use existing dosen or create mapping
            if (isset($existingDosens[$index])) {
                $dosen = $existingDosens[$index];
            } else {
                // If not enough dosens, skip
                $this->command->warn("Skipping: {$data['nama']} (no dosen available)");
                continue;
            }
            
            // Create or update prestasi
            PrestasiDosen::updateOrCreate(
                [
                    'dosen_id' => $dosen->id,
                    'tahun' => $tahun,
                ],
                [
                    'publikasi' => $data['publikasi'],
                    'hibah' => $data['hibah'],
                    'skor_sinta' => $data['skor_sinta'],
                    'buku' => $data['buku'],
                ]
            );
            
            $this->command->info("✓ [{$dosen->nama}] mapped to data: {$data['nama']}");
        }
        
        $this->command->info('');
        $this->command->info('✅ Successfully seeded ' . count($prestasiData) . ' prestasi records!');
        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info('   Max Publikasi: 4');
        $this->command->info('   Max Hibah: Rp 188,514,000');
        $this->command->info('   Max Skor SINTA: 541');
        $this->command->info('   Max Buku: 9');
    }
}
