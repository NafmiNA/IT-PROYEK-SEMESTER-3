<?php

namespace App\Filament\Resources\Penelitians\Pages;

use App\Filament\Resources\Penelitians\PenelitianResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPenelitians extends ListRecords
{
    protected static string $resource = PenelitianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
