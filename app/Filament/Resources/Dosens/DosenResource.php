<?php

namespace App\Filament\Resources\Dosens;

use App\Filament\Resources\Dosens\Pages\CreateDosen;
use App\Filament\Resources\Dosens\Pages\EditDosen;
use App\Filament\Resources\Dosens\Pages\ListDosens;
use App\Filament\Resources\Dosens\Schemas\DosenForm;
use App\Filament\Resources\Dosens\Tables\DosensTable;
use App\Models\Dosen;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder; // <-- TAMBAHAN: Import class Builder


class DosenResource extends Resource
{

    protected static ?string $model = Dosen::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?string $navigationLabel = 'Dosen';

    protected static ?string $modelLabel = 'Dosen';

    protected static ?string $pluralModelLabel = 'Dosen';

    public static function form(Schema $schema): Schema
    {
        return DosenForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DosensTable::configure($table);
    }

    // --- TAMBAHAN UNTUK MEMPERCEPAT QUERY ---
    /**
     * Menerapkan Eager Loading untuk relasi 'user'.
     * Ini memperbaiki N+1 Query Problem yang menyebabkan tabel lambat.
     */
    public static function getEloquentQuery(): Builder
    {
        // Asumsi nama relasi di App/Models/Dosen.php adalah 'user'
        // Jika berbeda (misal: 'pengguna'), sesuaikan 'user' di bawah ini.
        return parent::getEloquentQuery()
            ->with(['user']);
    }
    // --- AKHIR TAMBAHAN ---

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDosens::route('/'),
            'create' => CreateDosen::route('/create'),
            'edit' => EditDosen::route('/{record}/edit'),
        ];
    }
}
