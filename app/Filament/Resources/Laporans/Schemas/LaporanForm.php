<?php

namespace App\Filament\Resources\Laporans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LaporanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('periode')
                    ->required(),
                Select::make('jenis')
                    ->options(['Penelitian' => 'Penelitian', 'Pengabdian' => 'Pengabdian', 'Gabungan' => 'Gabungan'])
                    ->default('Gabungan')
                    ->required(),
                TextInput::make('file_path')
                    ->default(null),
                DatePicker::make('tanggal_publish'),
            ]);
    }
}
