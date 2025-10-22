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

class PengabdianResource extends Resource
{
    protected static ?string $model = Pengabdian::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'judul';

    public static function form(Schema $schema): Schema
    {
        return PengabdianForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PengabdiansTable::configure($table);
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
            'index' => ListPengabdians::route('/'),
            'create' => CreatePengabdian::route('/create'),
            'edit' => EditPengabdian::route('/{record}/edit'),
        ];
    }
}
