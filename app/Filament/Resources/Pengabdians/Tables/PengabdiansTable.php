<?php

namespace App\Filament\Resources\Pengabdians\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
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
                    ->color('warning')
                    ->visible(fn ($record) => $record->status === 'Menunggu')
                    ->modalHeading('Verifikasi Pengabdian')
                    ->modalDescription(fn ($record) => "Verifikasi pengabdian: {$record->judul}")
                    ->modalSubmitActionLabel('Simpan Verifikasi')
                    ->modalWidth('lg')
                    ->form([
                        \Filament\Forms\Components\Radio::make('status')
                            ->label('Keputusan Verifikasi')
                            ->options([
                                'Disetujui' => 'Setujui Pengabdian',
                                'Ditolak' => 'Tolak Pengabdian',
                            ])
                            ->required()
                            ->inline()
                            ->descriptions([
                                'Disetujui' => 'Pengabdian akan disetujui dan dapat dilanjutkan',
                                'Ditolak' => 'Pengabdian akan ditolak dan tidak dapat dilanjutkan',
                            ])
                            ->columnSpanFull(),
                        \Filament\Forms\Components\Textarea::make('catatan')
                            ->label('Catatan Verifikasi')
                            ->rows(3)
                            ->placeholder('Tambahkan catatan verifikasi (opsional)')
                            ->helperText('Catatan ini akan membantu dosen memahami keputusan verifikasi')
                            ->columnSpanFull(),
                    ])
                    ->action(function ($record, array $data) {
                        // Update status
                        $record->update([
                            'status' => $data['status'],
                        ]);
                        
                        // Send WhatsApp notification to dosen
                        try {
                            $whatsapp = new \App\Services\WhatsAppService();
                            $whatsappSent = $whatsapp->sendVerificationNotification(
                                $record->dosen,
                                $record,
                                $data['status'],
                                $data['catatan'] ?? null
                            );
                        } catch (\Exception $e) {
                            \Log::error('WhatsApp notification failed: ' . $e->getMessage());
                            $whatsappSent = false;
                        }
                        
                        // Notification dengan warna sesuai keputusan
                        $notification = \Filament\Notifications\Notification::make()
                            ->title('Verifikasi Berhasil');
                        
                        if ($data['status'] === 'Disetujui') {
                            $notification
                                ->success()
                                ->body("Pengabdian '{$record->judul}' telah DISETUJUI");
                        } else {
                            $notification
                                ->warning()
                                ->body("Pengabdian '{$record->judul}' telah DITOLAK");
                        }
                        
                        // Add WhatsApp status to notification
                        if ($whatsappSent) {
                            $notification->body($notification->getBody() . "\n✅ WhatsApp terkirim ke dosen");
                        } else {
                            $notification->body($notification->getBody() . "\n⚠️  WhatsApp gagal dikirim (cek log)");
                        }
                        
                        if (!empty($data['catatan'])) {
                            $notification->body($notification->getBody() . "\nCatatan: {$data['catatan']}");
                        }
                        
                        $notification->send();
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
