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
// -----------------------------------------------------------------


/*
|--------------------------------------------------------------------------
| Redirect root ke dashboard dosen
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    // NANTI ANDA PERLU UBAH INI:
    // Buat controller baru untuk mengarahkan ke dashboard
    // yang sesuai (Admin, Dosen, atau Mahasiswa) setelah login.
    
    // Untuk sekarang, kita arahkan ke halaman login
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
| Setelah login arahkan ke dashboard dosen
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'prevent.back'])->group(function () {

    // ========================================================================
    // MODIFIKASI: Rute '/dashboard' (setelah login) sekarang "PINTAR"
    // ========================================================================
    Route::get('/dashboard', function () {
        $role = Auth::user()->role; // Ambil role user yang login

        if ($role == 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role == 'dosen') {
            return redirect()->route('dosen.dashboard');
        } elseif ($role == 'mahasiswa') {
            return redirect()->route('mahasiswa.dashboard');
        } else {
            // Jika role tidak dikenal, logout saja
            Auth::logout();
            return redirect()->route('login')->with('error', 'Role tidak dikenal.');
        }
    })->name('dashboard'); // <-- INI ADALAH RUTE 'dashboard' YANG SEBENARNYA

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
    // Area ADMIN (BARU) (prefix: /admin , name: admin.*)
    // ========================================================================
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        
        // Dashboard Admin (Halaman Utama Admin)
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
             ->name('dashboard');

        // Kelola Akun Pengguna (Use Case Admin)
        Route::resource('users', UserController::class);

        // Rute untuk Kelola Penelitian (Admin)
        Route::resource('penelitian', AdminPenelitianController::class)
             ->names('penelitian') // ->name('admin.penelitian.index'), dll.
             ->parameters(['penelitian' => 'penelitian']);
        
        // Rute untuk Kelola Pengabdian (Admin)
        Route::resource('pengabdian', AdminPengabdianController::class)
             ->names('pengabdian') // ->name('admin.pengabdian.index'), dll.
             ->parameters(['pengabdian' => 'pengabdian']);

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