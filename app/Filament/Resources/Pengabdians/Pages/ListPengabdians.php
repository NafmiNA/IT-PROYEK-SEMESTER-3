<?php

namespace App\Filament\Resources\Pengabdians\Pages;

use App\Filament\Resources\Pengabdians\PengabdianResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPengabdians extends ListRecords
{
    protected static string $resource = PengabdianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
