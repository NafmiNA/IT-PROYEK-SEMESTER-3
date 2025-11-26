<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\AhpPerbandingan;
use App\Services\AhpService;
use Illuminate\Http\Request;

class AhpController extends Controller
{
    protected $ahpService;
    
    public function __construct(AhpService $ahpService)
    {
        $this->ahpService = $ahpService;
    }

    /**
     * Tampilkan form perbandingan AHP
     */
    public function index()
    {
        $kriteria = Kriteria::active()->orderBy('id')->get();
        
        // Get existing comparisons
        $perbandingan = AhpPerbandingan::with(['kriteriaA', 'kriteriaB'])->get();
        
        // Get current weights
        $bobot = $this->ahpService->getCurrentWeights();
        
        // Build comparison pairs (unique pairs only)
        $pairs = [];
        $kriteriaCount = $kriteria->count();
        
        for ($i = 0; $i < $kriteriaCount; $i++) {
            for ($j = $i + 1; $j < $kriteriaCount; $j++) {
                $kriteriaA = $kriteria[$i];
                $kriteriaB = $kriteria[$j];
                
                // Find existing comparison
                $existing = $perbandingan->first(function($item) use ($kriteriaA, $kriteriaB) {
                    return ($item->kriteria_a_id == $kriteriaA->id && $item->kriteria_b_id == $kriteriaB->id) ||
                           ($item->kriteria_a_id == $kriteriaB->id && $item->kriteria_b_id == $kriteriaA->id);
                });
                
                $nilai = 1; // default: sama penting
                if ($existing) {
                    if ($existing->kriteria_a_id == $kriteriaA->id) {
                        $nilai = $existing->nilai;
                    } else {
                        $nilai = 1 / $existing->nilai; // reciprocal
                    }
                }
                
                $pairs[] = [
                    'kriteria_a' => $kriteriaA,
                    'kriteria_b' => $kriteriaB,
                    'nilai' => $nilai
                ];
            }
        }
        
        return view('admin.ahp.index', compact('kriteria', 'pairs', 'bobot'));
    }

    /**
     * Save comparison value
     */
    public function saveComparison(Request $request)
    {
        $request->validate([
            'kriteria_a_id' => 'required|exists:kriteria,id',
            'kriteria_b_id' => 'required|exists:kriteria,id',
            'nilai' => 'required|numeric|min:0.111|max:9', // 1/9 sampai 9
        ]);
        
        try {
            $this->ahpService->saveComparison(
                $request->kriteria_a_id,
                $request->kriteria_b_id,
                $request->nilai,
                auth()->id()
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Perbandingan berhasil disimpan'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate AHP weights
     */
    public function calculate()
    {
        try {
            $result = $this->ahpService->calculate();
            
            if ($result['is_consistent']) {
                $message = 'Perhitungan bobot berhasil! CR = ' . number_format($result['consistency_ratio'], 4) . ' (Konsisten)';
            } else {
                $message = 'Perhitungan selesai, namun CR = ' . number_format($result['consistency_ratio'], 4) . ' (Tidak konsisten, sebaiknya revisi perbandingan)';
            }
            
            return redirect()->route('admin.ahp.index')
                ->with('success', $message)
                ->with('calculation_result', $result);
                
        } catch (\Exception $e) {
            return redirect()->route('admin.ahp.index')
                ->with('error', 'Gagal menghitung bobot: ' . $e->getMessage());
        }
    }

    /**
     * Show detailed calculation results
     */
    public function showResults()
    {
        try {
            $data = $this->ahpService->buildMatrix();
            $matrix = $data['matrix'];
            $kriteria = $data['kriteria'];
            $n = $data['n'];
            
            $normalized = $this->ahpService->normalizeMatrix($matrix, $n);
            $weights = $this->ahpService->calculateWeights($normalized, $n);
            $lambdaMax = $this->ahpService->calculateLambdaMax($matrix, $weights, $n);
            $ci = $this->ahpService->calculateCI($lambdaMax, $n);
            $cr = $this->ahpService->calculateCR($ci, $n);
            
            return view('admin.ahp.results', compact(
                'kriteria', 
                'matrix', 
                'normalized', 
                'weights', 
                'lambdaMax', 
                'ci', 
                'cr', 
                'n'
            ));
            
        } catch (\Exception $e) {
            return redirect()->route('admin.ahp.index')
                ->with('error', 'Gagal menampilkan hasil: ' . $e->getMessage());
        }
    }
}
