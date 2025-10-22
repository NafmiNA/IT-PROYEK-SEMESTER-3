<?php

namespace App\Filament\Resources\PrestasiDosens;

use App\Filament\Resources\PrestasiDosens\Pages\CreatePrestasiDosen;
use App\Filament\Resources\PrestasiDosens\Pages\EditPrestasiDosen;
use App\Filament\Resources\PrestasiDosens\Pages\ListPrestasiDosens;
use App\Filament\Resources\PrestasiDosens\Schemas\PrestasiDosenForm;
use App\Filament\Resources\PrestasiDosens\Tables\PrestasiDosensTable;
use App\Models\PrestasiDosen;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PrestasiDosenResource extends Resource
{
    protected static ?string $model = PrestasiDosen::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    
    protected static ?string $navigationLabel = 'Prestasi Dosen';
    
    protected static ?string $modelLabel = 'Prestasi Dosen';
    
    protected static ?string $pluralModelLabel = 'Prestasi Dosen';
    
    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return PrestasiDosenForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PrestasiDosensTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPrestasiDosens::route('/'),
            'create' => CreatePrestasiDosen::route('/create'),
            'edit' => EditPrestasiDosen::route('/{record}/edit'),
        ];
    }
}
