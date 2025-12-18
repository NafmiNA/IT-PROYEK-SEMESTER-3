<?php

// MODIFIKASI: Namespace diubah ke Admin
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\PrestasiDosen;
use App\Services\AhpService;
// (Model Penelitian & Pengabdian tidak diperlukan di controller ini)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrestasiDosenController extends Controller
{
    protected $ahpService;
    
    public function __construct(AhpService $ahpService)
    {
        $this->ahpService = $ahpService;
    }
    
    /**
     * Menampilkan daftar SEMUA prestasi dari SEMUA dosen (Global Admin View).
     */
    public function index(Request $request)
    {
        // Sinkronisasi data real-time dari Penelitian & Pengabdian
        $this->syncPrestasiData();

        // Ambil data admin yang login (untuk variabel $dosen di view)
        $dosen = Auth::user(); 
        
        // Ambil list tahun unik untuk filter
        $availableYears = PrestasiDosen::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Query dasar
        $query = PrestasiDosen::with('dosen');

        // Apply filter tahun jika ada
        if ($request->has('year') && $request->year != '') {
            $query->where('tahun', $request->year);
        }

        // Ambil data prestasi, diurutkan, dan dipaginasi
        $prestasi = $query->orderBy('tahun', 'desc')
            ->paginate(15); 

        // Variabel dummy ini dibutuhkan oleh view kloningan Anda
        $currentYear = date('Y');
        $currentPrestasi = [
            'publikasi' => $prestasi->sum('publikasi'),
            'hibah' => $prestasi->sum('hibah'),
            'skor_sinta' => $prestasi->sum('skor_sinta'),
            'buku' => $prestasi->sum('buku'),
        ];

        // Ambil bobot kriteria dari AHP
        $bobot = $this->ahpService->getCurrentWeights();

        // MODIFIKASI: Mengarah ke view 'admin.prestasi.index'
        return view('admin.prestasi.index', compact('prestasi', 'currentPrestasi', 'currentYear', 'dosen', 'bobot', 'availableYears'));
    }

    /**
     * Sinkronisasi data prestasi dari tabel Penelitian dan Pengabdian
     */
    private function syncPrestasiData()
    {
        $dosens = Dosen::all();

        foreach ($dosens as $dosen) {
            // 1. Ambil SEMUA data di mana dosen terlibat (Ketua ATAU Anggota) untuk hitung Publikasi
            $allPenelitian = $dosen->penelitian()->get(); 
            $allPengabdianApproved = $dosen->pengabdians()->where('status', 'Disetujui')->get();
            
            // 2. Ambil data di mana dosen adalah KETUA saja untuk hitung Hibah (Dana)
            $penelitianKetua = $dosen->penelitianKetua()->get();
            $pengabdianKetuaApproved = $dosen->pengabdianKetua()->where('status', 'Disetujui')->get();

            // Ambil semua tahun unik dari keterlibatan dosen
            $years = $allPenelitian->pluck('tahun')
                           ->merge($allPengabdianApproved->pluck('tahun'))
                           ->unique()
                           ->filter()
                           ->values();

            foreach ($years as $year) {
                // Hitung Publikasi (Semua keterlibatan)
                $countPublikasi = $allPenelitian->where('tahun', $year)->count() 
                                + $allPengabdianApproved->where('tahun', $year)->count();

                // Hitung Hibah (Hanya sebagai Ketua agar tidak double counting dana sistem)
                $totalHibah = $penelitianKetua->where('tahun', $year)->sum('dana') 
                            + $pengabdianKetuaApproved->where('tahun', $year)->sum('dana');

                $prestasi = PrestasiDosen::firstOrNew([
                    'dosen_id' => $dosen->id,
                    'tahun' => $year
                ]);

                $prestasi->publikasi = $countPublikasi;
                $prestasi->hibah = $totalHibah;
                
                if (!$prestasi->exists) {
                    $prestasi->skor_sinta = 0;
                    $prestasi->buku = 0;
                }
                
                $prestasi->save();
            }
        }
    }

    // MODIFIKASI: Fungsi store, edit, update, dan calculatePrestasi dihapus.
    // Sesuai Use Case, Admin tidak mengelola data ini, hanya melihat (Global).
    // (Jika Admin perlu mengelola, kita harus mengkloning logikanya seperti
    // Penelitian/Pengabdian, namun saat ini kita ikuti Use Case)
}