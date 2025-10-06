<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Dokumentasi; // Import Model Dokumentasi Anda untuk mengambil data

class MahasiswaDashboardController extends Controller
{
    /**
     * Menampilkan halaman Dashboard Mahasiswa (Beranda Penelitian).
     * Ini adalah metode utama yang diakses ketika mahasiswa login.
     */
    public function index()
    {
        // 1. Ambil Data Mahasiswa yang Sedang Login
        // GANTI INI: Gunakan Auth::user() atau logika otentikasi Anda yang sebenarnya
        $mahasiswa = (object)[
            'id' => 1,
            'nama' => 'Nurlaila',
        ];

        // 2. Ambil Data Penelitian/Dokumentasi yang Terkait
        // GANTI INI: Ganti data statis ini dengan query database yang memfilter berdasarkan mahasiswa_id
        
        /* Contoh jika Anda menggunakan database:
         * $penelitian = Dokumentasi::where('mahasiswa_id', $mahasiswa->id)
         * ->orderBy('tahun', 'desc')
         * ->get();
         */
        
        $penelitian = [
            (object)[
                'id' => 1, 
                'judul' => 'Analisis Data Penduduk Kalsel', 
                'dosen' => 'Jaka Permadi, S.Si., M.Kom', 
                'status' => 'Selesai', 
                'tahun' => 2025, 
                'peran' => 'Kontributor'
            ],
            (object)[
                'id' => 2, 
                'judul' => 'Sistem informasi Desa Digital', 
                'dosen' => 'Nindy Permatasari, S.Kom., M.Kom', 
                'status' => 'Berjalan', 
                'tahun' => 2024, 
                'peran' => 'Anggota'
            ],
        ];

        // 3. Kirim data ke View
        return view('mahasiswa.dashboard', compact('penelitian', 'mahasiswa'));
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