<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dosen\DashboardController;
use App\Http\Controllers\Dosen\PenelitianController;
use App\Http\Controllers\Dosen\PengabdianController;
use App\Http\Controllers\Dosen\DokumentasiController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


/*
|--------------------------------------------------------------------------
| Redirect root ke dashboard dosen
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('dosen.dashboard'));

/*
|--------------------------------------------------------------------------
| Route bawaan Breeze untuk autentikasi
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Halaman dashboard umum (dipakai setelah login)
| Otomatis arahkan ke dashboard dosen sesuai role
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return redirect()->route('dosen.dashboard');
})->middleware(['auth','verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Route untuk pengaturan profil user
|--------------------------------------------------------------------------
*/

// routes/web.php


Route::middleware(['auth'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::resource('penelitian', PenelitianController::class)->only([
        'index','create','store','show','edit','update','destroy'
    ]);
});


/*
|--------------------------------------------------------------------------
| Area Dosen
| Semua route dengan prefix /dosen dan nama dosen.*
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','verified','role:dosen'])
    ->prefix('dosen')
    ->as('dosen.')
    ->group(function () {

        // Dashboard Dosen
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Kelola Penelitian
        Route::resource('penelitian', PenelitianController::class);

        // Kelola Pengabdian
        Route::resource('pengabdian', PengabdianController::class);
    });

    Route::prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('/penelitian', PenelitianController::class);
        Route::resource('/pengabdian', PengabdianController::class);
        Route::resource('/dokumentasi', DokumentasiController::class);
    });

    Route::middleware(['auth','role:dosen'])
    ->prefix('dosen')->name('dosen.')
    ->group(function () {
        Route::resource('pengabdian', PengabdianController::class);
    });

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::get('/forgot-password', fn()=>view('auth.forgot-password'))->name('password.request');

// (opsional) Google OAuth
Route::get('/auth/google/redirect', [\App\Http\Controllers\Auth\GoogleController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\GoogleController::class, 'callback']);

