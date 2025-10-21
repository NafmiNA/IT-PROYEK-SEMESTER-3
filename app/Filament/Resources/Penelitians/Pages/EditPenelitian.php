<?php

namespace App\Filament\Resources\Penelitians\Pages;

use App\Filament\Resources\Penelitians\PenelitianResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPenelitian extends EditRecord
{
    protected static string $resource = PenelitianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
