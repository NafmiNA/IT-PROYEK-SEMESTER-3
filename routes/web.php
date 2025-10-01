<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dosen\DashboardController;
use App\Http\Controllers\Dosen\PenelitianController;
use App\Http\Controllers\Dosen\PengabdianController;
use App\Http\Controllers\Dosen\DokumentasiController;

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
Route::middleware('auth')->group(function () {
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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