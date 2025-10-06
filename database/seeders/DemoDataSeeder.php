<?php

namespace Database\Seeders;

use App\Models\Dokumentasi;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Cache dosen instances keyed by identifier.
     *
     * @var array<string, \App\Models\Dosen>
     */
    protected array $dosens = [];
    protected array $mahasiswaUsers = [];

    public function run(): void
    {
        $this->seedAdmin();
        $this->seedDosens();
        $this->seedPenelitian();
        $this->seedPengabdian();
        $this->seedMahasiswa();
    }

    protected function seedAdmin(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@kampus.ac.id'],
            [
                'name'              => 'Administrator P3M',
                'password'          => Hash::make('password123'),
                'role'              => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }

    protected function seedDosens(): void
    {
        $dosens = [
            [
                'key'    => 'andi',
                'nidn'   => '1234567890',
                'name'   => 'Andi Dosen',
                'email'  => 'andi@kampus.ac.id',
                'status' => true,
            ],
            [
                'key'    => 'siti',
                'nidn'   => '9876543210',
                'name'   => 'Siti Rahma',
                'email'  => 'siti@kampus.ac.id',
                'status' => true,
            ],
            [
                'key'    => 'budi',
                'nidn'   => '1928374650',
                'name'   => 'Budi Santoso',
                'email'  => 'budi@kampus.ac.id',
                'status' => true,
            ],
        ];

        foreach ($dosens as $dosenData) {
            $user = User::updateOrCreate(
                ['email' => $dosenData['email']],
                [
                    'name'              => $dosenData['name'],
                    'password'          => Hash::make('password123'),
                    'role'              => 'dosen',
                    'email_verified_at' => now(),
                ]
            );

            $dosen = Dosen::updateOrCreate(
                ['nidn' => $dosenData['nidn']],
                [
                    'nama'         => $dosenData['name'],
                    'email'        => $dosenData['email'],
                    'status_aktif' => $dosenData['status'],
                    'user_id'      => $user->id,
                ]
            );

            $this->dosens[$dosenData['key']] = $dosen;
        }
    }

    protected function seedPenelitian(): void
    {
        $penelitianList = [
            [
                'judul'       => 'Sistem Monitoring Kegiatan Akademik Berbasis IoT',
                'tahun'       => now()->year,
                'skema'       => 'Penelitian Terapan',
                'sumber_dana' => 'DRPM',
                'dana'        => 25000000,
                'status'      => 'Disetujui',
                'ketua'       => 'andi',
                'anggota'     => ['siti'],
                'docs'        => ['monitoring-kickoff.jpg', 'monitoring-laporan.jpg'],
            ],
            [
                'judul'       => 'Analisis Kesiapan Industri 4.0 pada UMKM Lokal',
                'tahun'       => now()->year - 1,
                'skema'       => 'Penelitian Dasar',
                'sumber_dana' => 'Internal Politala',
                'dana'        => 18000000,
                'status'      => 'Menunggu',
                'ketua'       => 'siti',
                'anggota'     => ['andi', 'budi'],
                'docs'        => ['umkm-survey.jpg'],
            ],
        ];

        foreach ($penelitianList as $data) {
            $ketua = $this->dosens[$data['ketua']] ?? null;
            if (!$ketua) {
                continue;
            }

            $penelitian = Penelitian::updateOrCreate(
                ['judul' => $data['judul']],
                [
                    'tahun'       => $data['tahun'],
                    'skema'       => $data['skema'],
                    'sumber_dana' => $data['sumber_dana'],
                    'dana'        => $data['dana'],
                    'status'      => $data['status'],
                    'dosen_id'    => $ketua->id,
                ]
            );

            $sync = [$ketua->id => ['peran' => 'Ketua']];
            foreach ($data['anggota'] as $key) {
                $anggota = $this->dosens[$key] ?? null;
                if ($anggota && $anggota->id !== $ketua->id) {
                    $sync[$anggota->id] = ['peran' => 'Anggota'];
                }
            }
            $penelitian->dosens()->sync($sync);

            foreach ($data['docs'] as $docName) {
                Dokumentasi::updateOrCreate(
                    [
                        'penelitian_id' => $penelitian->id,
                        'file_name'     => $docName,
                    ],
                    [
                        'mime'        => 'image/jpeg',
                        'size'        => 512000,
                        'gdrive_path' => "penelitian/{$penelitian->id}/{$docName}",
                    ]
                );
            }
        }
    }

    protected function seedPengabdian(): void
    {
        $pengabdianList = [
            [
                'judul'       => 'Pelatihan Literasi Digital untuk Guru SMK',
                'tahun'       => now()->year,
                'bidang'      => 'Pendidikan',
                'skema'       => 'Pengabdian Internal',
                'sumber_dana' => 'Internal Politala',
                'dana'        => 12000000,
                'status'      => 'Menunggu',
                'ketua'       => 'budi',
                'anggota'     => ['andi'],
                'docs'        => ['literasi-workshop.jpg'],
            ],
            [
                'judul'       => 'Pemberdayaan UMKM Melalui Marketplace Lokal',
                'tahun'       => now()->year - 1,
                'bidang'      => 'Ekonomi Kreatif',
                'skema'       => 'Kemitraan Masyarakat',
                'sumber_dana' => 'DRPM',
                'dana'        => 15000000,
                'status'      => 'Disetujui',
                'ketua'       => 'andi',
                'anggota'     => ['siti', 'budi'],
                'docs'        => ['umkm-marketplace.jpg', 'umkm-pelatihan.jpg'],
            ],
        ];

        foreach ($pengabdianList as $data) {
            $ketua = $this->dosens[$data['ketua']] ?? null;
            if (!$ketua) {
                continue;
            }

            $pengabdian = Pengabdian::updateOrCreate(
                ['judul' => $data['judul']],
                [
                    'tahun'       => $data['tahun'],
                    'bidang'      => $data['bidang'],
                    'skema'       => $data['skema'],
                    'sumber_dana' => $data['sumber_dana'],
                    'dana'        => $data['dana'],
                    'status'      => $data['status'],
                    'dosen_id'    => $ketua->id,
                ]
            );

            $sync = [$ketua->id => ['peran' => 'Ketua']];
            foreach ($data['anggota'] as $key) {
                $anggota = $this->dosens[$key] ?? null;
                if ($anggota && $anggota->id !== $ketua->id) {
                    $sync[$anggota->id] = ['peran' => 'Anggota'];
                }
            }
            $pengabdian->dosens()->sync($sync);

            foreach ($data['docs'] as $docName) {
                Dokumentasi::updateOrCreate(
                    [
                        'pengabdian_id' => $pengabdian->id,
                        'file_name'     => $docName,
                    ],
                    [
                        'mime'        => 'image/jpeg',
                        'size'        => 512000,
                        'gdrive_path' => "pengabdian/{$pengabdian->id}/{$docName}",
                    ]
                );
            }
        }
    }

    protected function seedMahasiswa(): void
    {
        $mahasiswaList = [
            [
                'name'   => 'Nurlaila Putri',
                'email'  => 'nurlaila@mhs.ac.id',
                'status' => 'Aktif',
                'tahun'  => (string) now()->year,
                'peran'  => 'Anggota',
            ],
            [
                'name'   => 'Rangga Rahman',
                'email'  => 'rangga@mhs.ac.id',
                'status' => 'Aktif',
                'tahun'  => (string) (now()->year - 1),
                'peran'  => 'Kontributor',
            ],
        ];

        foreach ($mahasiswaList as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'password'          => Hash::make('password123'),
                    'role'              => 'mahasiswa',
                    'email_verified_at' => now(),
                ]
            );

            Mahasiswa::updateOrCreate(
                ['email' => $data['email']],
                [
                    'nama'   => $data['name'],
                    'status' => $data['status'],
                    'tahun'  => $data['tahun'],
                    'peran'  => $data['peran'],
                ]
            );

            $this->mahasiswaUsers[] = $user;
        }
    }
}
