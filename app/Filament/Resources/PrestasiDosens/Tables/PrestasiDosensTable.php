<?php

namespace App\Filament\Resources\PrestasiDosens\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PrestasiDosensTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dosen.nama')
                    ->label('Nama Dosen')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tahun')
                    ->label('Tahun')
                    ->sortable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('publikasi')
                    ->label('Publikasi')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('hibah')
                    ->label('Hibah')
                    ->money('IDR')
                    ->sortable()
                    ->alignRight(),
                TextColumn::make('skor_sinta')
                    ->label('Skor SINTA')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color(fn ($state) => $state > 100 ? 'success' : ($state > 50 ? 'warning' : 'gray')),
                TextColumn::make('buku')
                    ->label('Buku')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diupdate')
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
