<?php

namespace App\Filament\Resources\Dosens\Pages;

use App\Filament\Resources\Dosens\DosenResource;
use App\Filament\Resources\Dosens\Exports\DosenExporter; // <-- Panggil Exporter baru
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction; // <-- Gunakan namespace yang benar
use Filament\Resources\Pages\ListRecords;

class ListDosens extends ListRecords
{
    protected static string $resource = DosenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Panggil Exporter yang baru kita buat
            ExportAction::make()
                ->exporter(DosenExporter::class), // <-- Cukup panggil nama class-nya

            CreateAction::make(),
        ];
    }
}