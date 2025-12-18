<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers Umum
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

// Controllers Dosen
use App\Http\Controllers\Dosen\{
    DashboardController,
    PenelitianController,
    PengabdianController,
    DokumentasiController,
    PrestasiDosenController
};

// Controllers Mahasiswa
use App\Http\Controllers\MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\DokumentasiController as MahasiswaDokumentasiController;

// Controllers Admin
use App\Http\Controllers\Admin\AdminDashboardController;
// Perhatikan Alias ini (AdminPenelitianController) penting agar tidak bentrok dengan Dosen
use App\Http\Controllers\Admin\PenelitianController as AdminPenelitianController;
use App\Http\Controllers\Admin\PengabdianController as AdminPengabdianController;
use App\Http\Controllers\Admin\PrestasiDosenController as AdminPrestasiController;
use App\Http\Controllers\Admin\AhpController;
use App\Http\Controllers\Admin\CloudStorageController;

/*
|--------------------------------------------------------------------------
| Redirect root ke halaman login
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Route bawaan Breeze
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Grup Rute Utama (Setelah Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'prevent.back'])->group(function () {

    // Routing cerdas berdasarkan Role
    Route::get('/dashboard', function () {
        $role = Auth::user()->role;

        if ($role == 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role == 'dosen') {
            return redirect()->route('dosen.dashboard');
        } elseif ($role == 'mahasiswa') {
            return redirect()->route('mahasiswa.dashboard');
        } else {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Role tidak dikenal.');
        }
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Area DOSEN
    |--------------------------------------------------------------------------
    */
    Route::prefix('dosen')->name('dosen.')->middleware('role:dosen')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('penelitian', PenelitianController::class)
            ->parameters(['penelitian' => 'penelitian']);

        Route::resource('pengabdian', PengabdianController::class)
            ->parameters(['pengabdian' => 'pengabdian']);

        Route::resource('dokumentasi', DokumentasiController::class)
            ->only(['store', 'destroy']);
        
        // Prestasi Dosen
        Route::get('/prestasi', [PrestasiDosenController::class, 'index'])->name('prestasi.index');
    });

    /*
    |--------------------------------------------------------------------------
    | Area MAHASISWA
    |--------------------------------------------------------------------------
    */
    Route::prefix('mahasiswa')->name('mahasiswa.')->middleware('role:mahasiswa')->group(function () {
        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');

        // CRUD Dokumentasi Mahasiswa
        Route::get('/dokumentasi', [MahasiswaDokumentasiController::class, 'index'])
            ->name('dokumentasi.index');
        Route::get('/dokumentasi/create', [MahasiswaDokumentasiController::class, 'create'])
            ->name('dokumentasi.create');
        Route::post('/dokumentasi', [MahasiswaDokumentasiController::class, 'store'])
            ->name('dokumentasi.store');
        Route::get('/dokumentasi/{id}', [MahasiswaDokumentasiController::class, 'show'])
            ->name('dokumentasi.show');
        Route::get('/dokumentasi/{id}/edit', [MahasiswaDokumentasiController::class, 'edit'])
            ->name('dokumentasi.edit');
        Route::put('/dokumentasi/{id}', [MahasiswaDokumentasiController::class, 'update'])
            ->name('dokumentasi.update');
        Route::delete('/dokumentasi/{id}', [MahasiswaDokumentasiController::class, 'destroy'])
            ->name('dokumentasi.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Area ADMIN
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        
        Route::get('/', fn () => redirect()->route('admin.dashboard'));
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Kelola Users
        Route::resource('users', UserController::class);

        // ==========================================================
        // FITUR PENELITIAN ADMIN (Sudah Termasuk Tambah/Create)
        // ==========================================================
        Route::get('/penelitian/export/excel', [AdminPenelitianController::class, 'export'])
            ->name('penelitian.export');

        Route::resource('penelitian', AdminPenelitianController::class)
            ->parameters(['penelitian' => 'penelitian']);
        
        // ==========================================================
        // FITUR PENGABDIAN ADMIN
        // ==========================================================
        Route::resource('pengabdian', AdminPengabdianController::class)
            ->parameters(['pengabdian' => 'pengabdian']);
        
        Route::patch('/pengabdian/{pengabdian}/update-status', [AdminPengabdianController::class, 'updateStatus'])
            ->name('pengabdian.updateStatus');
        
        Route::get('/pengabdian/export/excel', [AdminPengabdianController::class, 'export'])
            ->name('pengabdian.export');

        // Prestasi
        Route::get('/prestasi', [AdminPrestasiController::class, 'index'])->name('prestasi.index');

        // AHP
        Route::prefix('ahp')->name('ahp.')->group(function () {
            Route::get('/', [AhpController::class, 'index'])->name('index');
            Route::post('/comparison', [AhpController::class, 'saveComparison'])->name('saveComparison');
            Route::post('/calculate', [AhpController::class, 'calculate'])->name('calculate');
            Route::get('/results', [AhpController::class, 'showResults'])->name('results');
        });

        // SAW (Simple Additive Weighting - Ranking Prestasi)
        Route::prefix('saw')->name('saw.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\SawController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\SawController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Admin\SawController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\SawController::class, 'destroy'])->name('destroy');
            Route::get('/ranking', [\App\Http\Controllers\Admin\SawController::class, 'ranking'])->name('ranking');
            Route::get('/export', [\App\Http\Controllers\Admin\SawController::class, 'export'])->name('export');
        });

        // Cloud Storage (Google Drive)
        Route::prefix('cloud-storage')->name('cloud-storage.')->group(function () {
            Route::get('/settings', [CloudStorageController::class, 'settings'])->name('settings');
            Route::get('/connect', [CloudStorageController::class, 'connect'])->name('connect');
            Route::get('/callback', [CloudStorageController::class, 'callback'])->name('callback');
            Route::post('/disconnect', [CloudStorageController::class, 'disconnect'])->name('disconnect');
            Route::post('/save-folders', [CloudStorageController::class, 'saveFolders'])->name('save-folders');
            Route::post('/create-custom-folder', [CloudStorageController::class, 'createCustomFolder'])->name('create-custom-folder');
            Route::get('/status', [CloudStorageController::class, 'getStatus'])->name('status');
        });

    });

});

/*
|--------------------------------------------------------------------------
| Profil User
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('penelitian/{penelitian}/upload', [PenelitianController::class, 'uploadDokumen'])
        ->name('penelitian.upload');
});
