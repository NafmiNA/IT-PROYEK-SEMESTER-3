<?php

namespace App\Filament\Resources\PrestasiDosens\Pages;

use App\Filament\Resources\PrestasiDosens\PrestasiDosenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPrestasiDosens extends ListRecords
{
    protected static string $resource = PrestasiDosenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
