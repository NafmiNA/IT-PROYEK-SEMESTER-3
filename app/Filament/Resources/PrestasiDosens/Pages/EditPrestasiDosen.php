<?php

namespace App\Filament\Resources\PrestasiDosens\Pages;

use App\Filament\Resources\PrestasiDosens\PrestasiDosenResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPrestasiDosen extends EditRecord
{
    protected static string $resource = PrestasiDosenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
