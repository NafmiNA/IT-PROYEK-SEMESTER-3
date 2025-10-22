<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;

class DosenBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dosenData = [
            ['nama' => 'Afian Syafaadi Rizki, M.Kom', 'publikasi' => 0, 'hibah' => 0, 'skor_sinta' => 7, 'buku' => 0],
            ['nama' => 'Ir. Agustian Noor, M.Kom', 'publikasi' => 0, 'hibah' => 69367000, 'skor_sinta' => 274, 'buku' => 0],
            ['nama' => 'Aidil Fajar Zulhfari, S.Kom., M.Kom', 'publikasi' => 0, 'hibah' => 7500000, 'skor_sinta' => 47, 'buku' => 0],
            ['nama' => 'Billy Sabella, S.Kom., M.Kom', 'publikasi' => 0, 'hibah' => 29253000, 'skor_sinta' => 0, 'buku' => 0],
            ['nama' => 'Cahya Karima, M.Kom', 'publikasi' => 0, 'hibah' => 0, 'skor_sinta' => 4, 'buku' => 3],
            ['nama' => 'Dwi Agung Wibowo, M.Kom', 'publikasi' => 1, 'hibah' => 18050000, 'skor_sinta' => 6, 'buku' => 1],
            ['nama' => 'Herfia Rhomadhona, S.Kom., M.Cs', 'publikasi' => 1, 'hibah' => 82909000, 'skor_sinta' => 190, 'buku' => 3],
            ['nama' => 'Jaka Permadi, S.Si., M.Cs', 'publikasi' => 2, 'hibah' => 666996000, 'skor_sinta' => 0, 'buku' => 5],
            ['nama' => 'Khairul Anwar Hafidz, M.Kom', 'publikasi' => 0, 'hibah' => 0, 'skor_sinta' => 541, 'buku' => 1],
            ['nama' => 'Mamed Rofendi Manalu, M.Kom', 'publikasi' => 1, 'hibah' => 19849000, 'skor_sinta' => 77, 'buku' => 1],
            ['nama' => 'Nina Mia Aristti, M.Kom', 'publikasi' => 0, 'hibah' => 0, 'skor_sinta' => 44, 'buku' => 1],
            ['nama' => 'Oky Rahmanto, S.Kom., M.T', 'publikasi' => 2, 'hibah' => 45000000, 'skor_sinta' => 99, 'buku' => 1],
            ['nama' => 'Rabini Sayyidati, M.Pd', 'publikasi' => 2, 'hibah' => 42823000, 'skor_sinta' => 0, 'buku' => 0],
            ['nama' => 'Sausan Hidayah Nova, S.Kom., M.Kom', 'publikasi' => 1, 'hibah' => 0, 'skor_sinta' => 18, 'buku' => 0],
            ['nama' => 'Veri Julianto, M.Si', 'publikasi' => 1, 'hibah' => 188514000, 'skor_sinta' => 180, 'buku' => 4],
            ['nama' => 'Winda Aprianti, M.Si', 'publikasi' => 4, 'hibah' => 95853000, 'skor_sinta' => 32, 'buku' => 1],
            ['nama' => 'Wiwik Kusmini, S.Kom., M.Cs', 'publikasi' => 0, 'hibah' => 0, 'skor_sinta' => 0, 'buku' => 0],
            ['nama' => 'Yunita Prastyaniyah, M.Kom', 'publikasi' => 1, 'hibah' => 10000000, 'skor_sinta' => 9, 'buku' => 1],
            ['nama' => 'Zaenul Mutagin, M.M.S.I', 'publikasi' => 0, 'hibah' => 0, 'skor_sinta' => 0, 'buku' => 0],
        ];

        echo "Creating 19 Dosen accounts...\n";
        
        foreach ($dosenData as $index => $data) {
            // Generate email dari nama
            $email = $this->generateEmail($data['nama']);
            
            // Generate NIDN (contoh: 0001234567 + index)
            $nidn = str_pad((1000000000 + $index + 1), 10, '0', STR_PAD_LEFT);
            
            // Create User
            $user = User::create([
                'name' => $data['nama'],
                'email' => $email,
                'password' => Hash::make('password123'), // default password
                'role' => 'dosen',
            ]);
            
            // Create Dosen
            Dosen::create([
                'user_id' => $user->id,
                'nidn' => $nidn,
                'nama' => $data['nama'],
                'email' => $email,
                'jabatan_fungsional' => null, // Kosongkan dulu
                'status_aktif' => true,
            ]);
            
            echo "✓ Created: {$data['nama']} ({$email})\n";
        }
        
        echo "\nCreating 1 Mahasiswa account (Nindy Permatasari)...\n";
        
        // Create User untuk Mahasiswa
        $emailMhs = $this->generateEmail($mahasiswaData['nama']);
        $userMhs = User::create([
            'name' => $mahasiswaData['nama'],
            'email' => $emailMhs,
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa',
        ]);
        
        // Create Mahasiswa (adjusted to table structure)
        Mahasiswa::create([
            'nama' => $mahasiswaData['nama'],
            'email' => $emailMhs,
            'status' => 'Aktif',
            'tahun' => (string)$mahasiswaData['angkatan'],
            'peran' => 'Mahasiswa',
            'role' => 'mahasiswa',
        ]);
        
        echo "✓ Created: {$mahasiswaData['nama']} ({$emailMhs}) - MAHASISWA\n";
        
        echo "\n✅ Seeder completed!\n";
        echo "Total created: 19 Dosen + 1 Mahasiswa = 20 accounts\n";
        echo "\nDefault password for all: password123\n";
    }
    
    /**
     * Generate email dari nama
     */
    private function generateEmail(string $nama): string
    {
        // Ambil nama depan dan belakang
        $parts = explode(' ', $nama);
        
        // Ambil nama pertama dan kedua (skip gelar)
        $firstName = '';
        $lastName = '';
        
        foreach ($parts as $part) {
            // Skip gelar
            if (in_array($part, ['Ir.', 'S.Kom.', 'S.Kom.,', 'M.Kom', 'M.Kom,', 'M.Cs', 'M.Si', 'M.Pd', 'M.T', 'M.M.S.I', 'S.Si.,'])) {
                continue;
            }
            
            if (empty($firstName)) {
                $firstName = $part;
            } else if (empty($lastName) && !empty($part)) {
                $lastName = $part;
                break; // Cukup 2 nama
            }
        }
        
        // Combine dan bersihkan
        $name = strtolower($firstName . $lastName);
        $name = str_replace([',', '.'], '', $name);
        
        return $name . '@politala.ac.id';
    }
    
    /**
     * Get jabatan from gelar (DISABLED - leave empty for manual input)
     */
    private function getJabatanFromGelar(string $nama): ?string
    {
        // Dikosongkan dulu, akan diisi manual nanti
        return null;
        
        // Old logic (commented out):
        // if (str_contains($nama, 'Ir.')) {
        //     return 'Lektor Kepala';
        // } else if (str_contains($nama, 'M.Cs') || str_contains($nama, 'M.T')) {
        //     return 'Lektor';
        // } else if (str_contains($nama, 'M.Kom') || str_contains($nama, 'M.Si') || str_contains($nama, 'M.Pd')) {
        //     return 'Asisten Ahli';
        // } else {
        //     return 'Tenaga Pengajar';
        // }
    }
}
