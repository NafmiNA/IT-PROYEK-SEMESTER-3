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
require _DIR_ . '/auth.php';

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
    | Area MAHASISWA (prefix: /mahasiswa , name: mahasiswa.*)
    |--------------------------------------------------------------------------
    */
    Route::prefix('mahasiswa')->name('mahasiswa.')->middleware('role:mahasiswa')->group(function () {
        Route::get('/dashboard', [MahasiswaController::class, 'index'])->name('dashboard');
        Route::get('/create',   [MahasiswaController::class, 'create'])->name('create');
        Route::post('/',        [MahasiswaController::class, 'store'])->name('store');
        Route::delete('/{id}',  [MahasiswaController::class, 'destroy'])->name('destroy');
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