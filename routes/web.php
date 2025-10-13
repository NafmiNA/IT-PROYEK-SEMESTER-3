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
use App\Http\Controllers\MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\DokumentasiController as MahasiswaDokumentasiController;

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

        // Kelola Dokumentasi (hanya store & destroy)
        Route::resource('dokumentasi', DokumentasiController::class)
            ->only(['store', 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Area MAHASISWA (prefix: /mahasiswa , name: mahasiswa.*)
    |--------------------------------------------------------------------------
    */
   Route::prefix('mahasiswa')->name('mahasiswa.')->middleware('role:mahasiswa')->group(function () {
        // Dashboard Mahasiswa
        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])
            ->name('dashboard');

        // CRUD Dokumentasi Mahasiswa
        Route::get('/dokumentasi', [MahasiswaDokumentasiController::class, 'index'])
            ->name('dokumentasi.index');
        Route::get('/dokumentasi/create', [MahasiswaDokumentasiController::class, 'create'])
            ->name('dokumentasi.create');
        Route::post('/dokumentasi', [MahasiswaDokumentasiController::class, 'store'])
            ->name('dokumentasi.store');
        Route::get('/dokumentasi/{id}/edit', [MahasiswaDokumentasiController::class, 'edit'])
            ->name('dokumentasi.edit');
        Route::put('/dokumentasi/{id}', [MahasiswaDokumentasiController::class, 'update'])
            ->name('dokumentasi.update');
        Route::delete('/dokumentasi/{id}', [MahasiswaDokumentasiController::class, 'destroy'])
            ->name('dokumentasi.destroy');
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

    // Upload dokumen penelitian (khusus dosen)
    Route::post('penelitian/{penelitian}/upload', [PenelitianController::class, 'uploadDokumen'])
        ->name('penelitian.upload');
});