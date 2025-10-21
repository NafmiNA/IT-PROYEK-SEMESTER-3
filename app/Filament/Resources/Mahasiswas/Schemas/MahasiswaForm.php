<?php

namespace App\Filament\Resources\Mahasiswas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MahasiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('status')
                    ->default(null),
                TextInput::make('tahun')
                    ->default(null),
                TextInput::make('peran')
                    ->default(null),
                TextInput::make('role')
                    ->required()
                    ->default('mahasiswa'),
            ]);
    }
}
