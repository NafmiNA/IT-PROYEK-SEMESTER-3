<?php

namespace App\Exports;

use App\Models\Pengabdian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PengabdianExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Mengambil semua data pengabdian, termasuk relasi 'ketua'
        return Pengabdian::with('ketua')->get();
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        // Mendefinisikan judul kolom di file Excel
        return [
            'ID',
            'Judul',
            'Ketua',
            'Tahun',
            'Skema',
            'Sumber Dana',
            'Dana (Rp)',
            'Status',
        ];
    }

    /**
    * @param mixed $pengabdian
    * @return array
    */
    public function map($pengabdian): array
    {
        // Memetakan data dari collection ke setiap baris di Excel
        return [
            $pengabdian->id,
            $pengabdian->judul,
            $pengabdian->ketua->nama ?? 'N/A', // Mengambil nama dari relasi 'ketua'
            $pengabdian->tahun,
            $pengabdian->skema,
            $pengabdian->sumber_dana,
            $pengabdian->dana,
            $pengabdian->status,
        ];
    }
}