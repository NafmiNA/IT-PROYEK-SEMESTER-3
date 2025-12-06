<?php

namespace App\Exports;

use App\Models\Penelitian;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PenelitianExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
     * Query data dari database
     */
    public function query()
    {
        // Mulai query dasar dan load relasi agar hemat memori
        return Penelitian::query()->with(['ketua', 'dosens', 'mahasiswas'])->latest();
    }

    /**
     * Judul Header Kolom di Excel
     */
    public function headings(): array
    {
        return [
            'ID',
            'Judul Penelitian',
            'Ketua Peneliti',
            'Anggota Dosen',
            'Mahasiswa Terlibat',
            'Tahun',
            'Skema',
            'Sumber Dana',
            'Jumlah Dana (Rp)',
            'Status',
            'Link Jurnal',
            'Tanggal Input',
        ];
    }

    /**
     * Mapping data per baris
     */
    public function map($penelitian): array
    {
        // Ambil nama anggota dosen (selain ketua)
        $anggota = $penelitian->dosens
            ->where('id', '!=', $penelitian->dosen_id)
            ->pluck('nama')
            ->implode(', ');

        // Ambil nama mahasiswa
        $mahasiswa = $penelitian->mahasiswas
            ->pluck('nama')
            ->implode(', ');

        return [
            $penelitian->id,
            $penelitian->judul,
            $penelitian->ketua->nama ?? 'Tidak Ada',
            $anggota ?: '-',
            $mahasiswa ?: '-',
            $penelitian->tahun,
            $penelitian->skema,
            $penelitian->sumber_dana,
            $penelitian->dana,
            $penelitian->status,
            $penelitian->link_jurnal,
            $penelitian->created_at ? $penelitian->created_at->format('d-m-Y') : '-',
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
