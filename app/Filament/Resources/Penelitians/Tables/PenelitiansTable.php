<?php

namespace App\Filament\Resources\Penelitians\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PenelitiansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')
                    ->searchable(),
                TextColumn::make('tahun')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('skema')
                    ->searchable(),
                TextColumn::make('sumber_dana')
                    ->searchable(),
                TextColumn::make('dana')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('laporan_path')
                    ->searchable(),
                TextColumn::make('link_jurnal')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('dosen_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
