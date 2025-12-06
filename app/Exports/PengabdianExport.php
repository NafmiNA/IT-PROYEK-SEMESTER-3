<?php

namespace App\Exports;

use App\Models\Pengabdian;
use Maatwebsite\Excel\Concerns\FromQuery; // PENTING: Gunakan FromQuery, bukan FromCollection
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PengabdianExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $tahun;
    protected $status;
    protected $skema;

    // Constructor: Menerima data filter yang dikirim dari Controller
    public function __construct($tahun = null, $status = null, $skema = null)
    {
        $this->tahun = $tahun;
        $this->status = $status;
        $this->skema = $skema;
    }

    /**
     * Query data dari database dengan filter
     */
    public function query()
    {
        // Mulai query dasar dan load relasi agar hemat memori
        $query = Pengabdian::query()->with(['ketua', 'dosens', 'mahasiswas']);

        // 1. Filter Tahun
        if ($this->tahun && $this->tahun != 'all') {
            $query->where('tahun', $this->tahun);
        }

        // 2. Filter Status
        if ($this->status && $this->status != 'all') {
            $query->where('status', $this->status);
        }

        // 3. Filter Skema
        if ($this->skema && $this->skema != 'all') {
            $query->where('skema', $this->skema);
        }

        // Urutkan dari yang paling baru
        return $query->latest();
    }

    /**
     * Judul Header Kolom di Excel
     */
    public function headings(): array
    {
        return [
            'ID',
            'Judul Pengabdian',
            'Ketua Pelaksana',
            'Anggota Dosen',
            'Mahasiswa Terlibat',
            'Tahun',
            'Skema',
            'Sumber Dana',
            'Jumlah Dana (Rp)',
            'Status',
            'Tanggal Input',
        ];
    }

    /**
     * Mapping data per baris
     */
    public function map($pengabdian): array
    {
        // Ambil nama anggota dosen (selain ketua)
        $anggota = $pengabdian->dosens
            ->where('id', '!=', $pengabdian->dosen_id)
            ->pluck('nama')
            ->implode(', ');

        // Ambil nama mahasiswa
        $mahasiswa = $pengabdian->mahasiswas
            ->pluck('nama')
            ->implode(', ');

        return [
            $pengabdian->id,
            $pengabdian->judul,
            $pengabdian->ketua->nama ?? 'Tidak Ada', // Nama Ketua
            $anggota ?: '-',                         // Nama Anggota
            $mahasiswa ?: '-',                       // Nama Mahasiswa
            $pengabdian->tahun,
            $pengabdian->skema,
            $pengabdian->sumber_dana,
            $pengabdian->dana,
            $pengabdian->status,
            $pengabdian->created_at ? $pengabdian->created_at->format('d-m-Y') : '-',
        ];
    }

    /**
     * Membuat Header (Baris 1) menjadi Huruf Tebal (Bold)
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}