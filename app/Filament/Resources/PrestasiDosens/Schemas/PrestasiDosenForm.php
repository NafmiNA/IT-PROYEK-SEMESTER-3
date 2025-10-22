<?php

namespace App\Filament\Resources\PrestasiDosens\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PrestasiDosenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('dosen_id')
                    ->label('Nama Dosen')
                    ->relationship('dosen', 'nama')
                    ->searchable()
                    ->required()
                    ->preload(),
                TextInput::make('tahun')
                    ->label('Tahun')
                    ->required()
                    ->numeric()
                    ->minValue(2000)
                    ->maxValue(2100)
                    ->default(date('Y')),
                TextInput::make('publikasi')
                    ->label('Jumlah Publikasi')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->disabled()
                    ->dehydrated()
                    ->helperText('⚠️ Auto-calculated dari penelitian'),
                TextInput::make('hibah')
                    ->label('Total Hibah (Rp)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->prefix('Rp')
                    ->disabled()
                    ->dehydrated()
                    ->helperText('⚠️ Auto-calculated dari penelitian + pengabdian disetujui'),
                TextInput::make('skor_sinta')
                    ->label('Skor SINTA')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->helperText('Skor SINTA dosen'),
                TextInput::make('buku')
                    ->label('Jumlah Buku')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->helperText('Jumlah buku yang diterbitkan'),
            ]);
    }
}
