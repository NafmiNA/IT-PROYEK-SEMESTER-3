<?php

namespace App\Filament\Resources\Penelitians\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PenelitianForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('judul')
                    ->required(),
                TextInput::make('tahun')
                    ->required()
                    ->numeric(),
                TextInput::make('skema')
                    ->default(null),
                TextInput::make('sumber_dana')
                    ->default(null),
                TextInput::make('dana')
                    ->numeric()
                    ->default(null),
                TextInput::make('laporan_path')
                    ->default(null),
                TextInput::make('link_jurnal')
                    ->default(null),
                Select::make('status')
                    ->options([
            'Draft' => 'Draft',
            'Menunggu' => 'Menunggu',
            'Disetujui' => 'Disetujui',
            'Ditolak' => 'Ditolak',
        ])
                    ->default('Draft')
                    ->required(),
                TextInput::make('dosen_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
