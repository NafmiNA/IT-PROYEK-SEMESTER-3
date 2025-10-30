<?php

namespace App\Filament\Resources\Pengabdians;

use App\Filament\Resources\Pengabdians\Pages\CreatePengabdian;
use App\Filament\Resources\Pengabdians\Pages\EditPengabdian;
use App\Filament\Resources\Pengabdians\Pages\ListPengabdians;
use App\Filament\Resources\Pengabdians\Schemas\PengabdianForm;
use App\Filament\Resources\Pengabdians\Tables\PengabdiansTable;
use App\Models\Pengabdian;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder; // <-- TAMBAHAN: Import class Builder

class PengabdianResource extends Resource
{
    protected static ?string $model = Pengabdian::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'judul';

    protected static ?string $navigationLabel = 'Pengabdian';

    protected static ?string $modelLabel = 'Pengabdian';

    protected static ?string $pluralModelLabel = 'Pengabdian';

    protected static bool $shouldRegisterNavigation = true;

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return PengabdianForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PengabdiansTable::configure($table);
    }

    // --- TAMBAHAN UNTUK MEMPERCEPAT QUERY ---
    /**
     * Menerapkan Eager Loading untuk relasi 'dosen'.
     * Ini memperbaiki N+1 Query Problem yang menyebabkan tabel lambat.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['dosen']); // Kita HANYA perlu load relasi 'dosen'
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
            'index' => ListPengabdians::route('/'),
            // 'create' => CreatePengabdian::route('/create'), // Disabled: Admin tidak bisa create pengabdian
            'edit' => EditPengabdian::route('/{record}/edit'),
        ];
    }
    
    public static function canCreate(): bool
    {
        return false; // Disable create button
    }
}

