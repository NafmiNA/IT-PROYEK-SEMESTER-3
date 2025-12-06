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
            // Hitung Publikasi (Jumlah Penelitian Disetujui)
            $publikasi = Penelitian::where('dosen_id', $dosenId)
                ->where('tahun', $tahun)
                ->where('status', 'Disetujui')
                ->count();
    
            // Hitung Hibah (Total Dana Penelitian + Pengabdian Disetujui)
            // Asumsi: Dosen yang login adalah Ketua (dosen_id pada tabel penelitian/pengabdian)
            $danaPenelitian = Penelitian::where('dosen_id', $dosenId)
                ->where('tahun', $tahun)
                ->where('status', 'Disetujui')
                ->sum('dana');
    
            $danaPengabdian = Pengabdian::where('dosen_id', $dosenId)
                ->where('tahun', $tahun)
                ->where('status', 'Disetujui')
                ->sum('dana');
    
            $hibah = $danaPenelitian + $danaPengabdian;
    
            return [$publikasi, $hibah];
        }
    }
