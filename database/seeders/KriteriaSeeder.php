<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;

class KriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Kriteria penilaian dosen berprestasi
     */
    public function run(): void
    {
        $kriteria = [
            [
                'kode' => 'K1',
                'nama' => 'Publikasi',
                'deskripsi' => 'Jumlah publikasi ilmiah (jurnal, prosiding, artikel)',
                'tipe' => 'benefit',
                'is_active' => true
            ],
            [
                'kode' => 'K2',
                'nama' => 'Skor SINTA',
                'deskripsi' => 'Skor SINTA dosen yang menunjukkan produktivitas riset',
                'tipe' => 'benefit',
                'is_active' => true
            ],
            [
                'kode' => 'K3',
                'nama' => 'Hibah',
                'deskripsi' => 'Jumlah hibah penelitian yang diperoleh',
                'tipe' => 'benefit',
                'is_active' => true
            ],
            [
                'kode' => 'K4',
                'nama' => 'Buku',
                'deskripsi' => 'Jumlah buku yang telah dipublikasikan',
                'tipe' => 'benefit',
                'is_active' => true
            ],
        ];

        foreach ($kriteria as $k) {
            Kriteria::create($k);
        }
    }
}
