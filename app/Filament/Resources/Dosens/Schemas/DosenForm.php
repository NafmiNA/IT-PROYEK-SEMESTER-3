<?php

namespace App\Filament\Resources\Dosens\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DosenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nidn')
                    ->default(null),
                TextInput::make('nama')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('jabatan_fungsional')
                    ->default(null),
                Toggle::make('status_aktif')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
            ]);
    }
}
