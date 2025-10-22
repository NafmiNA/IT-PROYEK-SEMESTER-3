<?php

namespace App\Filament\Resources\Pengabdians\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;
use Filament\Forms\Components\Select;

class PengabdianForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Input Dosen (Sudah Benar)
                Select::make('dosen_id')
                    ->relationship('dosen', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),

                // Input Judul (Ditambahkan Kembali)
                TextInput::make('judul')
                    ->required()
                    ->columnSpanFull(), // Agar judul lebar penuh

                // Input Tahun (Ditambahkan Kembali)
                TextInput::make('tahun')
                    ->numeric() // Pastikan numeric
                    ->required(),

                // Input Bidang (Ditambahkan Kembali)
                TextInput::make('bidang')
                    ->default(null),

                // Input Skema (Ditambahkan Kembali)
                TextInput::make('skema')
                    ->default(null),

                // Input Sumber Dana (Ditambahkan Kembali)
                TextInput::make('sumber_dana')
                    ->default(null),

                // Input Dana (Sudah Benar dengan Masking)
                TextInput::make('dana')
                    ->numeric()
                    ->prefix('Rp')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    // ->stripCharacters('.') // Aktifkan jika pemisah Anda titik
                    ->default(null),

                // Input Status (Sudah Benar jadi Select)
                Select::make('status')
                    ->options([
                        'Draft' => 'Draft',
                        'Menunggu' => 'Menunggu',
                        'Disetujui' => 'Disetujui',
                        'Ditolak' => 'Ditolak',
                    ])
                    ->required()
                    ->default('Menunggu'),
            ])
            // Tambahkan ->columns(2) agar form terbagi jadi 2 kolom (lebih rapi)
            ->columns(2);
    }
}

