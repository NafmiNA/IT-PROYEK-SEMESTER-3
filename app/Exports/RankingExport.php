<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RankingExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $ranking;

    public function __construct($ranking)
    {
        $this->ranking = $ranking;
    }

    /**
     * Return the collection of ranking data
     */
    public function collection()
    {
        return $this->ranking;
    }

    /**
     * Header columns
     */
    public function headings(): array
    {
        return [
            'Rank',
            'Nama Dosen',
            'NIP',
            'Skor Akhir (SAW)',
            'Publikasi (Asli)',
            'Skor SINTA (Asli)',
            'Hibah (Asli)',
            'Buku (Asli)',
        ];
    }

    /**
     * Map data per row
     */
    public function map($item): array
    {
        $dosen = $item['dosen'];
        $raw = $item['raw_data'];

        return [
            $item['rank'],
            $dosen->nama,
            $dosen->nip,
            number_format($item['skor_akhir'], 4),
            $raw['publikasi'],
            $raw['skor_sinta'],
            $raw['hibah'],
            $raw['buku'],
        ];
    }

    /**
     * Styles
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
