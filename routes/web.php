<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dosen\{
    DashboardController,
    PenelitianController,
    PengabdianController,
    DokumentasiController
};

/*
|--------------------------------------------------------------------------
| Redirect root ke dashboard dosen
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('dosen.dashboard'));

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

    Route::get('/dashboard', fn () => redirect()->route('dosen.dashboard'))
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Area DOSEN  (prefix: /dosen , name: dosen.*)
    |--------------------------------------------------------------------------
    */
    Route::prefix('dosen')->name('dosen.')->middleware('role:dosen')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Penelitian
        Route::resource('penelitian', PenelitianController::class)
            ->names('penelitian')        // dosen.penelitian.index|create|store|...
            ->parameters(['penelitian' => 'penelitian']);

        // Pengabdian
        Route::resource('pengabdian', PengabdianController::class)
            ->names('pengabdian')
            ->parameters(['pengabdian' => 'pengabdian']);

        // Dokumentasi (batasi sesuai kebutuhan)
        Route::resource('dokumentasi', DokumentasiController::class)
            ->only(['store','destroy']);
    });
});
