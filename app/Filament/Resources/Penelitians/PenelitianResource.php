<?php

namespace App\Filament\Resources\Penelitians;

use App\Filament\Resources\Penelitians\Pages\CreatePenelitian;
use App\Filament\Resources\Penelitians\Pages\EditPenelitian;
use App\Filament\Resources\Penelitians\Pages\ListPenelitians;
use App\Filament\Resources\Penelitians\Schemas\PenelitianForm;
use App\Filament\Resources\Penelitians\Tables\PenelitiansTable;
use App\Models\Penelitian;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PenelitianResource extends Resource
{
    protected static ?string $model = Penelitian::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'judul';

    public static function form(Schema $schema): Schema
    {
        return PenelitianForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PenelitiansTable::configure($table);
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
            'index' => ListPenelitians::route('/'),
            'create' => CreatePenelitian::route('/create'),
            'edit' => EditPenelitian::route('/{record}/edit'),
        ];
    }
}
