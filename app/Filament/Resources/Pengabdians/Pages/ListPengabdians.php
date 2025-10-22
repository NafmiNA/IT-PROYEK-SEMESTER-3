<?php

namespace App\Filament\Resources\Pengabdians\Pages;

use App\Filament\Resources\Pengabdians\PengabdianResource;
use App\Filament\Resources\Pengabdians\Exports\PengabdianExporter; // <-- Panggil Exporter baru
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction; // <-- Gunakan namespace yang benar
use Filament\Resources\Pages\ListRecords;

class ListPengabdians extends ListRecords
{
    protected static string $resource = PengabdianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Panggil Exporter yang baru kita buat
            ExportAction::make()
                ->exporter(PengabdianExporter::class), // <-- Panggil class Exporter Pengabdian

            CreateAction::make(),
        ];
    }
}