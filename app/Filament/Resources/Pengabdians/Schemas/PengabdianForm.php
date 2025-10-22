<?php

namespace App\Filament\Resources\Pengabdians\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\RawJs; // <-- 1. TAMBAHKAN 'USE' INI

class PengabdianForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('dosen_id') // Nanti kita ganti jadi Select
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

                // --- PERUBAHAN DI SINI ---
                TextInput::make('dana')
                    ->numeric()
                    ->prefix('Rp') // Opsional: Tambahkan prefix
                    ->mask(RawJs::make('$money($input)')) // <-- 2. TAMBAHKAN MASKING
                    ->stripCharacters(',') // <-- 3. HAPUS KOMA SAAT SIMPAN
                    // ->stripCharacters('.') // <-- Atau titik jika perlu
                    ->default(null),
                // --- SELESAI PERUBAHAN ---

                TextInput::make('status') // Nanti kita ganti jadi Select
                    ->required()
                    ->default('Menunggu'),
            ]);
    }
}