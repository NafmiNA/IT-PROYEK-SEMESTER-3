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
        $dosens = Dosen::with(['penelitian', 'pengabdians'])->get();

        foreach ($dosens as $dosen) {
            // Ambil semua tahun unik dari kedua sumber
            $years = collect();
            
            // Ambil data yang disetujui
            $penelitian = $dosen->penelitian()->where('status', 'Disetujui')->get();
            $pengabdian = $dosen->pengabdians()->where('status', 'Disetujui')->get();
            
            $years = $years->merge($penelitian->pluck('tahun'))
                           ->merge($pengabdian->pluck('tahun'))
                           ->unique()
                           ->filter()
                           ->values();

            foreach ($years as $year) {
                // Hitung statistik untuk tahun tersebut
                $p_year = $penelitian->where('tahun', $year);
                $ab_year = $pengabdian->where('tahun', $year);

                $countPublikasi = $p_year->count() + $ab_year->count();
                $totalHibah = $p_year->sum('dana') + $ab_year->sum('dana');

                // Update atau Create record prestasi
                $prestasi = PrestasiDosen::firstOrNew([
                    'dosen_id' => $dosen->id,
                    'tahun' => $year
                ]);

                $prestasi->publikasi = $countPublikasi;
                $prestasi->hibah = $totalHibah;
                
                // Set default jika null (untuk record baru)
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