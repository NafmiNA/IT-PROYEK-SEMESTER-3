<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrestasiDosen;
use App\Models\Dosen;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use App\Services\SawService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Exports\RankingExport;
use Maatwebsite\Excel\Facades\Excel;

class SawController extends Controller
{
    protected $sawService;
    
    public function __construct(SawService $sawService)
    {
        $this->sawService = $sawService;
    }
    
    /**
     * Display prestasi list and input form
     */
    public function index(Request $request)
    {
        // Sinkronisasi data real-time agar data yang disetujui langsung muncul
        $this->syncPrestasiData();

        $tahun = $request->get('tahun', now()->year);
        $years = $this->sawService->getAvailableYears();
        
        if ($years->isEmpty()) {
            $years = collect([now()->year]);
        }
        
        $prestasi = PrestasiDosen::with('dosen')
            ->where('tahun', $tahun)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $dosens = Dosen::orderBy('nama')->get();
        
        return view('admin.saw.index', compact('prestasi', 'dosens', 'tahun', 'years'));
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
    
    /**
     * Store new prestasi data
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dosen_id' => 'required|exists:dosens,id',
            'tahun' => 'required|integer|min:2000|max:' . (now()->year + 1),
            // 'publikasi' dan 'hibah' dihapus dari validasi input karena otomatis
            'skor_sinta' => 'required|integer|min:0',
            'buku' => 'required|integer|min:0',
        ]);
        
        try {
            // Hitung otomatis Publikasi dan Hibah
            [$publikasi, $hibah] = $this->calculatePublikasiAndHibah($validated['dosen_id'], $validated['tahun']);
            
            $dataToStore = array_merge($validated, [
                'publikasi' => $publikasi,
                'hibah' => $hibah,
            ]);

            PrestasiDosen::create($dataToStore);
            
            Log::info('Prestasi dosen created (auto-calculated)', $dataToStore);
            
            return redirect()
                ->route('admin.saw.index', ['tahun' => $validated['tahun']])
                ->with('success', 'Data prestasi berhasil ditambahkan! Publikasi dan Hibah dihitung otomatis.');
        } catch (\Exception $e) {
            Log::error('Failed to create prestasi', ['error' => $e->getMessage()]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }
    
        /**
         * Update existing prestasi
         */
        public function update(Request $request, $id)
        {
            $prestasi = PrestasiDosen::findOrFail($id);
            
            $validated = $request->validate([
                // Publikasi dan Hibah dihapus dari validasi karena otomatis
                'skor_sinta' => 'required|integer|min:0',
                'buku' => 'required|integer|min:0',
            ]);
            
            try {
                // Hitung ulang Publikasi dan Hibah (untuk memastikan data terbaru)
                [$publikasi, $hibah] = $this->calculatePublikasiAndHibah($prestasi->dosen_id, $prestasi->tahun);
                
                $dataToUpdate = array_merge($validated, [
                    'publikasi' => $publikasi,
                    'hibah' => $hibah,
                ]);
    
                $prestasi->update($dataToUpdate);
                
                Log::info('Prestasi dosen updated (auto-calculated)', ['id' => $id, 'data' => $dataToUpdate]);
                
                return redirect()
                    ->route('admin.saw.index', ['tahun' => $prestasi->tahun])
                    ->with('success', 'Data prestasi berhasil diperbarui! Publikasi dan Hibah dihitung ulang otomatis.');
            } catch (\Exception $e) {
                Log::error('Failed to update prestasi', ['id' => $id, 'error' => $e->getMessage()]);
                
                return redirect()
                    ->back()
                    ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
            }
        }
        
        /**
         * Delete prestasi
         */
        public function destroy($id)
        {
            $prestasi = PrestasiDosen::findOrFail($id);
            $tahun = $prestasi->tahun;
            
            try {
                $prestasi->delete();
                
                Log::info('Prestasi dosen deleted', ['id' => $id]);
                
                return redirect()
                    ->route('admin.saw.index', ['tahun' => $tahun])
                    ->with('success', 'Data prestasi berhasil dihapus!');
            } catch (\Exception $e) {
                Log::error('Failed to delete prestasi', ['id' => $id, 'error' => $e->getMessage()]);
                
                return redirect()
                    ->back()
                    ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
            }
        }
        
        /**
         * Display SAW ranking results
         */
        public function ranking(Request $request)
        {
            $tahun = $request->get('tahun', now()->year);
            $years = $this->sawService->getAvailableYears();
            
            if ($years->isEmpty()) {
                return redirect()
                    ->route('admin.saw.index')
                    ->with('warning', 'Belum ada data prestasi. Silakan input data terlebih dahulu.');
            }
            
            // Calculate ranking
            $ranking = $this->sawService->getRanking($tahun);
            
            if ($ranking->isEmpty()) {
                return redirect()
                    ->route('admin.saw.index', ['tahun' => $tahun])
                    ->with('warning', 'Belum ada data prestasi untuk tahun ' . $tahun);
            }
            
            // Get summary statistics
            $summary = $this->sawService->getSummary($tahun);
            
            // Get latest AHP weights
            $bobot = $this->sawService->getLatestWeights();
            
            return view('admin.saw.ranking', compact('ranking', 'tahun', 'years', 'summary', 'bobot'));
        }
    
        /**
         * Export ranking to Excel
         */
        public function export(Request $request)
        {
            $tahun = $request->get('tahun', now()->year);
            
            // Calculate ranking
            $ranking = $this->sawService->getRanking($tahun);
            
            if ($ranking->isEmpty()) {
                return redirect()
                    ->back()
                    ->with('error', 'Tidak ada data untuk diekspor pada tahun ' . $tahun);
            }
    
            return Excel::download(new RankingExport($ranking), 'ranking-dosen-berprestasi-' . $tahun . '.xlsx');
        }
    
        /**
         * Helper: Hitung Publikasi dan Hibah secara otomatis
         */
            private function calculatePublikasiAndHibah($dosenId, $tahun)
            {
                $dosen = Dosen::find($dosenId);
                if (!$dosen) return [0, 0];

                // 1. Publikasi (Semua keterlibatan: Ketua ATAU Anggota)
                $jmlPenelitian = Penelitian::whereHas('dosens', function($q) use ($dosenId) {
                        $q->where('dosen_id', $dosenId);
                    })
                    ->where('tahun', $tahun)
                    ->count();
        
                $jmlPengabdian = Pengabdian::whereHas('dosens', function($q) use ($dosenId) {
                        $q->where('dosen_id', $dosenId);
                    })
                    ->where('tahun', $tahun)
                    ->where('status', 'Disetujui')
                    ->count();
        
                $publikasi = $jmlPenelitian + $jmlPengabdian;
        
                // 2. Hibah (Hanya sebagai Ketua agar tidak double counting dana)
                $danaPenelitian = Penelitian::where('dosen_id', $dosenId)
                    ->where('tahun', $tahun)
                    ->sum('dana');
        
                $danaPengabdian = Pengabdian::where('dosen_id', $dosenId)
                    ->where('tahun', $tahun)
                    ->where('status', 'Disetujui')
                    ->sum('dana');
        
                $hibah = $danaPenelitian + $danaPengabdian;
        
                return [$publikasi, $hibah];
            }    }
