<?php

namespace App\Filament\Resources\Pengabdians\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PengabdiansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dosen_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('judul')
                    ->searchable(),
                TextColumn::make('tahun'),
                TextColumn::make('bidang')
                    ->searchable(),
                TextColumn::make('skema')
                    ->searchable(),
                TextColumn::make('sumber_dana')
                    ->searchable(),
                TextColumn::make('dana')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
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
