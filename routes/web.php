<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // <-- SAYA TAMBAHKAN INI

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dosen\{
    DashboardController,
    PenelitianController,
    PengabdianController,
    DokumentasiController
    // (PrestasiDosenController sudah kita pindah ke Admin)
};
use App\Http\Controllers\MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\DokumentasiController as MahasiswaDokumentasiController;

// -----------------------------------------------------------------
// Controller-controller BARU untuk Admin
// -----------------------------------------------------------------
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\PenelitianController as AdminPenelitianController;
use App\Http\Controllers\Admin\PengabdianController as AdminPengabdianController;
// ========================================================================
// TAMBAHAN BARU: Controller Prestasi Admin
// ========================================================================
use App\Http\Controllers\Admin\PrestasiDosenController as AdminPrestasiController;
use App\Http\Controllers\Admin\AhpController;
// ========================================================================

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
| Route bawaan Breeze (login, register, dsb.)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Grup Rute Utama (Setelah Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'prevent.back'])->group(function () {

    // ========================================================================
    // Rute '/dashboard' (setelah login) yang "PINTAR"
    // ========================================================================
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
        
        // Kelola Prestasi
        Route::get('/prestasi', [\App\Http\Controllers\Dosen\PrestasiDosenController::class, 'index'])->name('prestasi.index');
        Route::post('/prestasi', [\App\Http\Controllers\Dosen\PrestasiDosenController::class, 'store'])->name('prestasi.store');
        Route::get('/prestasi/{id}/edit', [\App\Http\Controllers\Dosen\PrestasiDosenController::class, 'edit'])->name('prestasi.edit');
        Route::put('/prestasi/{id}', [\App\Http\Controllers\Dosen\PrestasiDosenController::class, 'update'])->name('prestasi.update');
    });

    /*
    |--------------------------------------------------------------------------
    | Area MAHASISWA (prefix: /mahasiswa , name: mahasiswa.*)
    |--------------------------------------------------------------------------
    */
    Route::prefix('mahasiswa')->name('mahasiswa.')->middleware('role:mahasiswa')->group(function () {
        // ... (Rute Mahasiswa Anda) ...
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

    // ========================================================================
    // Area ADMIN (prefix: /admin , name: admin.*)
    // ========================================================================
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        
        // Redirect /admin ke /admin/dashboard
        Route::get('/', fn () => redirect()->route('admin.dashboard'));

        // Dashboard Admin (Halaman Utama Admin)
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Kelola Akun Pengguna (Use Case Admin)
        Route::resource('users', UserController::class);

        // Rute untuk Kelola Penelitian (Admin)
        Route::resource('penelitian', AdminPenelitianController::class)
            ->names('penelitian')
            ->parameters(['penelitian' => 'penelitian']);
        
        // ========================================================================
        // RUTE BARU: Untuk Setujui / Verifikasi / Tolak Penelitian
        // ========================================================================
        Route::patch('/penelitian/{penelitian}/update-status', [AdminPenelitianController::class, 'updateStatus'])
            ->name('penelitian.updateStatus');

        // Rute untuk Kelola Pengabdian (Admin)
        Route::resource('pengabdian', AdminPengabdianController::class)
            ->names('pengabdian')
            ->parameters(['pengabdian' => 'pengabdian']);
        
        // ========================================================================
        // RUTE BARU: Untuk Setujui / Verifikasi / Tolak Pengabdian
        // ========================================================================
        Route::patch('/pengabdian/{pengabdian}/update-status', [AdminPengabdianController::class, 'updateStatus'])
            ->name('pengabdian.updateStatus');
        
        Route::get('/pengabdian/export/excel', [AdminPengabdianController::class, 'export'])
            ->name('pengabdian.export');

        // ========================================================================
        // TAMBAHAN BARU: Rute untuk Prestasi Admin
        // ========================================================================
        Route::get('/prestasi', [AdminPrestasiController::class, 'index'])->name('prestasi.index');

        // ========================================================================
        // TAMBAHAN: Rute untuk AHP (Perhitungan Bobot Kriteria)
        // ========================================================================
        Route::prefix('ahp')->name('ahp.')->group(function () {
            Route::get('/', [AhpController::class, 'index'])->name('index');
            Route::post('/comparison', [AhpController::class, 'saveComparison'])->name('saveComparison');
            Route::post('/calculate', [AhpController::class, 'calculate'])->name('calculate');
            Route::get('/results', [AhpController::class, 'showResults'])->name('results');
        });

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
