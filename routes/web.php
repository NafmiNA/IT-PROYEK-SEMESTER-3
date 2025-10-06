<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dosen\{
    DashboardController,
    PenelitianController,
    PengabdianController,
    DokumentasiController
};
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MahasiswaDashboardController;

/*
|--------------------------------------------------------------------------
| Redirect root ke dashboard dosen
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('dosen.dashboard');
});

/*
|--------------------------------------------------------------------------
| Route bawaan Breeze (login, register, dsb.)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Setelah login arahkan ke dashboard dosen
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard umum → redirect ke dashboard dosen
    Route::get('/dashboard', fn () => redirect()->route('dosen.dashboard'))
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Area DOSEN (prefix: /dosen , name: dosen.*)
    |--------------------------------------------------------------------------
    */
    Route::prefix('dosen')->name('dosen.')->middleware('role:dosen')->group(function () {

        // Dashboard Dosen
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Kelola Penelitian
        Route::resource('penelitian', PenelitianController::class)
            ->names('penelitian')
            ->parameters(['penelitian' => 'penelitian']);

        // Kelola Pengabdian
        Route::resource('pengabdian', PengabdianController::class)
            ->names('pengabdian')
            ->parameters(['pengabdian' => 'pengabdian']);

        // Kelola Dokumentasi (dibatasi sesuai kebutuhan)
        Route::resource('dokumentasi', DokumentasiController::class)
            ->only(['store', 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Area MAHASISWA (resource /mahasiswa)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('/mahasiswa/dashboard', [MahasiswaDashboardController::class, 'index'])
            ->name('mahasiswa.dashboard');

        Route::resource('mahasiswa', MahasiswaController::class)->except(['show']);
    });
});

/*
|--------------------------------------------------------------------------
| Pengaturan profil user
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
