<?php

namespace App\Filament\Resources\Dosens\Exports;

use App\Models\Dosen;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class DosenExporter extends Exporter
{
    protected static ?string $model = Dosen::class;

    public static function getColumns(): array
    {
        // Definisikan kolom yang mau diekspor di sini
        return [
            ExportColumn::make('nidn')->label('NIDN'),
            ExportColumn::make('nama')->label('Nama'),
            ExportColumn::make('email')->label('Email Address'),
            ExportColumn::make('jabatan_fungsional')->label('Jabatan Fungsional'),
            ExportColumn::make('status_aktif')
                ->label('Status Aktif')
                // PERBAIKAN DI SINI:
                ->formatStateUsing(function ($state): string { // <--- Ini yang diubah
                    return ($state === true || $state === 1) ? 'Aktif' : 'Tidak Aktif';
                }),
            // ExportColumn::make('user.name')->label('User Name'), // Aktifkan jika relasi 'user' sudah ada di Model Dosen
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Ekspor data dosen Anda telah selesai dan ' . number_format($export->successful_rows) . ' ' . str('baris')->plural($export->successful_rows) . ' berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('baris')->plural($failedRowsCount) . ' gagal diekspor.';
        }

        return $body;
    }

    // Nama file saat di-download (opsional)
    public function getFileName(Export $export): string
    {
        // Anda bisa sesuaikan nama filenya jika mau
        return "dosen-{$export->getKey()}";
    }
}