<?php

namespace App\Exports;

use App\Models\Pengabdian;
use Maatwebsite\Excel\Concerns\FromQuery; // PENTING: Gunakan FromQuery, bukan FromCollection
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PengabdianExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
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
        // Ambil nama anggota dosen (selain ketua) dan format dengan strip/newline
        $anggotaList = $pengabdian->dosens
            ->where('id', '!=', $pengabdian->dosen_id)
            ->pluck('nama');
        
        $anggotaFormatted = $anggotaList->count() > 0 
            ? $anggotaList->map(fn($nama) => "- " . $nama)->implode("\n")
            : '-';

        // Ambil nama mahasiswa dan format dengan strip/newline
        $mahasiswaList = $pengabdian->mahasiswas->pluck('nama');
        
        $mahasiswaFormatted = $mahasiswaList->count() > 0 
            ? $mahasiswaList->map(fn($nama) => "- " . $nama)->implode("\n")
            : '-';

        return [
            $pengabdian->id,
            $pengabdian->judul,
            $pengabdian->ketua->nama ?? 'Tidak Ada', // Nama Ketua
            $anggotaFormatted,                       // Nama Anggota
            $mahasiswaFormatted,                     // Nama Mahasiswa
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
                $event->sheet->getDelegate()->getStyle('A:K')
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
            },
        ];
    }
}