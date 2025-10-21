<?php

namespace App\Filament\Resources\Pengabdians\Pages;

use App\Filament\Resources\Pengabdians\PengabdianResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPengabdian extends EditRecord
{
    protected static string $resource = PengabdianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
