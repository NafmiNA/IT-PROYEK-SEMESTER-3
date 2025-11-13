<?php

// MODIFIKASI: Namespace diubah ke Admin
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\PrestasiDosen;
// (Model Penelitian & Pengabdian tidak diperlukan di controller ini)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrestasiDosenController extends Controller
{
    /**
     * Menampilkan daftar SEMUA prestasi dari SEMUA dosen (Global Admin View).
     */
    public function index()
    {
        // Ambil data admin yang login (untuk variabel $dosen di view)
        $dosen = Auth::user(); 
        
        // MODIFIKASI: Ambil SEMUA data prestasi, diurutkan, dan dipaginasi
        // Kita gunakan 'with('dosen')' untuk mengambil info dosen pemilik prestasi
        $prestasi = PrestasiDosen::with('dosen') 
            ->orderBy('tahun', 'desc')
            ->paginate(15); // Menampilkan 15 data per halaman

        // Variabel dummy ini dibutuhkan oleh view kloningan Anda
        $currentYear = date('Y');
        $currentPrestasi = [
            'publikasi' => 0,
            'hibah' => 0,
            'skor_sinta' => 0,
            'buku' => 0,
        ];

        // MODIFIKASI: Mengarah ke view 'admin.prestasi.index'
        return view('admin.prestasi.index', compact('prestasi', 'currentPrestasi', 'currentYear', 'dosen'));
    }

    // MODIFIKASI: Fungsi store, edit, update, dan calculatePrestasi dihapus.
    // Sesuai Use Case, Admin tidak mengelola data ini, hanya melihat (Global).
    // (Jika Admin perlu mengelola, kita harus mengkloning logikanya seperti
    // Penelitian/Pengabdian, namun saat ini kita ikuti Use Case)
}