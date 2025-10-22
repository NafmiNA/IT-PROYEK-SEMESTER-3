<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color; // <-- CUKUP SATU 'USE' STATEMENT INI
use Filament\Widgets\AccountWidget;
// use Filament\Widgets\FilamentInfoWidget; // Tetap di-comment jika tidak mau ditampilkan
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
// HAPUS 'use Filament\Support\Colors\Color;' yang duplikat dari sini

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            // ->login() 

            ->brandName('Dashboard Admin P3M TI Politala')

            // --- GANTI WARNA DI SINI ---
            ->colors([
                'primary' => Color::Blue, // Ganti Amber menjadi Emerald (atau warna lain)
            ])
            // --- SELESAI GANTI WARNA ---

            ->resources([
                \App\Filament\Resources\Dosens\DosenResource::class,
                \App\Filament\Resources\Mahasiswas\MahasiswaResource::class,
                \App\Filament\Resources\Penelitians\PenelitianResource::class,
                \App\Filament\Resources\Pengabdians\PengabdianResource::class,
                \App\Filament\Resources\PrestasiDosens\PrestasiDosenResource::class, 
                \App\Filament\Resources\Laporans\LaporanResource::class,
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                // FilamentInfoWidget::class, 
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                // \App\Http\Middleware\EnsureUserIsAdmin::class, 
            ]);
    }
}