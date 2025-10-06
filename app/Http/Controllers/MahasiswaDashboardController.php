<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaDashboardController extends Controller
{
    /**
     * Menampilkan halaman Dashboard Mahasiswa (Beranda Penelitian).
     * Ini adalah metode utama yang diakses ketika mahasiswa login.
     */
    public function index()
    {
        $user = Auth::user();

        $profilMahasiswa = Mahasiswa::firstWhere('email', $user?->email);

        $penelitian = Penelitian::with('ketua')->latest()->get();
        $pengabdian = Pengabdian::with('ketua')->latest()->get();

        return view('mahasiswa.dashboard', [
            'profilMahasiswa' => $profilMahasiswa,
            'penelitianList'  => $penelitian,
            'pengabdianList'  => $pengabdian,
        ]);
    }

    // ---
    
    /**
     * Metode untuk halaman Pengabdian Dosen (Arah navigasi sidebar).
     * Anda dapat menambahkan logika pengambilan data pengabdian di sini.
     */
    public function pengabdian()
    {
        // Logika pengambilan data pengabdian
        // ...
        
        return view('mahasiswa.pengabdian'); // Mengarahkan ke view 'resources/views/mahasiswa/pengabdian.blade.php'
    }
}
