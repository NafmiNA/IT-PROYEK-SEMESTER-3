<?php

namespace App\Filament\Resources\Pengabdians\Tables;

use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Support\Colors\Color;

class PengabdiansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dosen.nama')
                    ->label('Nama Dosen')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('judul')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('tahun')
                    ->sortable(),
                TextColumn::make('bidang')
                    ->searchable(),
                TextColumn::make('skema')
                    ->searchable(),
                TextColumn::make('sumber_dana')
                    ->searchable(),
                TextColumn::make('dana')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Draft' => 'gray',
                        'Menunggu' => 'warning',
                        'Disetujui' => 'success',
                        'Ditolak' => 'danger',
                        default => 'gray',
                    }),
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
                Action::make('verifikasi')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === 'Menunggu')
                    ->modalHeading('Verifikasi Pengabdian')
                    ->modalDescription(fn ($record) => "Verifikasi pengabdian: {$record->judul}")
                    ->modalSubmitActionLabel('Simpan')
                    ->form([
                        \Filament\Forms\Components\Select::make('status')
                            ->label('Status Verifikasi')
                            ->options([
                                'Disetujui' => 'Disetujui',
                                'Ditolak' => 'Ditolak',
                            ])
                            ->required(),
                        \Filament\Forms\Components\Textarea::make('catatan')
                            ->label('Catatan')
                            ->rows(3)
                            ->placeholder('Tambahkan catatan verifikasi (opsional)'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => $data['status'],
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Verifikasi Berhasil')
                            ->success()
                            ->body("Pengabdian telah {$data['status']}")
                            ->send();
                    }),
                    
                Action::make('setujui')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'Menunggu')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Pengabdian')
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menyetujui pengabdian: {$record->judul}?")
                    ->modalSubmitActionLabel('Ya, Setujui')
                    ->action(function ($record) {
                        $record->update(['status' => 'Disetujui']);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Pengabdian Disetujui')
                            ->success()
                            ->body('Pengabdian berhasil disetujui')
                            ->send();
                    }),
                    
                Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === 'Menunggu')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pengabdian')
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menolak pengabdian: {$record->judul}?")
                    ->modalSubmitActionLabel('Ya, Tolak')
                    ->action(function ($record) {
                        $record->update(['status' => 'Ditolak']);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Pengabdian Ditolak')
                            ->warning()
                            ->body('Pengabdian telah ditolak')
                            ->send();
                    }),
                    
                Action::make('lihat')
                    ->label('Lihat Detail')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn ($record) => route('filament.admin.resources.pengabdians.edit', $record))
                    ->openUrlInNewTab(false),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
