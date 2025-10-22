<?php

namespace App\Filament\Resources\Pengabdians\Exports; // <-- Sesuaikan namespace

use App\Models\Pengabdian; // <-- Gunakan Model Pengabdian
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PengabdianExporter extends Exporter // <-- Ganti nama class
{
    protected static ?string $model = Pengabdian::class; // <-- Gunakan Model Pengabdian

    public static function getColumns(): array
    {
        // Definisikan kolom untuk Pengabdian
        return [
            ExportColumn::make('dosen.nama')->label('Nama Dosen'), // Ambil nama dari relasi
            ExportColumn::make('judul')->label('Judul'),
            ExportColumn::make('tahun')->label('Tahun'),
            ExportColumn::make('bidang')->label('Bidang'),
            ExportColumn::make('skema')->label('Skema'),
            ExportColumn::make('sumber_dana')->label('Sumber Dana'),
            ExportColumn::make('dana')->label('Dana'), // Biarkan angka dulu
            ExportColumn::make('status')->label('Status'), // <-- SEDERHANAKAN: Ekspor teks status apa adanya
            // ExportColumn::make('status_aktif') // <-- Hapus formatState yang error
            //     ->label('Status Aktif')
            //     ->formatState(fn ($state): string => ($state === true || $state === 1) ? 'Aktif' : 'Tidak Aktif'), 
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        // Ubah pesan notifikasi
        $body = 'Ekspor data pengabdian Anda telah selesai dan ' . number_format($export->successful_rows) . ' ' . str('baris')->plural($export->successful_rows) . ' berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('baris')->plural($failedRowsCount) . ' gagal diekspor.';
        }

        return $body;
    }

    public function getFileName(Export $export): string
    {
        return "pengabdian-{$export->getKey()}"; // Ganti nama file
    }
}