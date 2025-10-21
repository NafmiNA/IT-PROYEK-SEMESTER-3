<?php

namespace App\Filament\Resources\Pengabdians\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PengabdianForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('dosen_id')
                    ->required()
                    ->numeric(),
                TextInput::make('judul')
                    ->required(),
                TextInput::make('tahun')
                    ->required(),
                TextInput::make('bidang')
                    ->default(null),
                TextInput::make('skema')
                    ->default(null),
                TextInput::make('sumber_dana')
                    ->default(null),
                TextInput::make('dana')
                    ->numeric()
                    ->default(null),
                TextInput::make('status')
                    ->required()
                    ->default('Menunggu'),
            ]);
    }
}
