<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrestasiDosen;
use App\Models\Dosen;
use App\Services\SawService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            'publikasi' => 'required|integer|min:0',
            'skor_sinta' => 'required|integer|min:0',
            'hibah' => 'required|integer|min:0',
            'buku' => 'required|integer|min:0',
        ]);
        
        try {
            PrestasiDosen::create($validated);
            
            Log::info('Prestasi dosen created', $validated);
            
            return redirect()
                ->route('admin.saw.index', ['tahun' => $validated['tahun']])
                ->with('success', 'Data prestasi berhasil ditambahkan!');
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
            'publikasi' => 'required|integer|min:0',
            'skor_sinta' => 'required|integer|min:0',
            'hibah' => 'required|integer|min:0',
            'buku' => 'required|integer|min:0',
        ]);
        
        try {
            $prestasi->update($validated);
            
            Log::info('Prestasi dosen updated', ['id' => $id, 'data' => $validated]);
            
            return redirect()
                ->route('admin.saw.index', ['tahun' => $prestasi->tahun])
                ->with('success', 'Data prestasi berhasil diperbarui!');
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
     * Export ranking to PDF or Excel (optional, implement later)
     */
    public function export(Request $request)
    {
        // TODO: Implement export functionality
        return redirect()->back()->with('info', 'Fitur export akan segera tersedia.');
    }
}
