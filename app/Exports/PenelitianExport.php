<?php

namespace App\Exports;

use App\Models\Penelitian;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PenelitianExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
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
        // Ambil nama anggota dosen (selain ketua) dan format dengan strip/newline
        $anggotaList = $penelitian->dosens
            ->where('id', '!=', $penelitian->dosen_id)
            ->pluck('nama');
        
        $anggotaFormatted = $anggotaList->count() > 0 
            ? $anggotaList->map(fn($nama) => "- " . $nama)->implode("\n")
            : '-';

        // Ambil nama mahasiswa dan format dengan strip/newline
        $mahasiswaList = $penelitian->mahasiswas->pluck('nama');
        
        $mahasiswaFormatted = $mahasiswaList->count() > 0 
            ? $mahasiswaList->map(fn($nama) => "- " . $nama)->implode("\n")
            : '-';

        return [
            $penelitian->id,
            $penelitian->judul,
            $penelitian->ketua->nama ?? 'Tidak Ada',
            $anggotaFormatted,
            $mahasiswaFormatted,
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

    /**
     * Aktifkan wrap text agar newline (\n) terlihat di Excel
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Kolom D (Anggota Dosen) dan E (Mahasiswa Terlibat)
                $event->sheet->getDelegate()->getStyle('D:E')
                    ->getAlignment()
                    ->setWrapText(true);
                
                // Set vertical alignment ke top agar rapi
                $event->sheet->getDelegate()->getStyle('A:L')
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
            },
        ];
    }
}
