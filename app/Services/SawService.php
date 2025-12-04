<?php

namespace App\Services;

use App\Models\Kriteria;
use App\Models\AhpBobot;
use App\Models\PrestasiDosen;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SawService
{
    /**
     * Calculate SAW ranking for a specific year
     * 
     * @param int $tahun
     * @return Collection
     */
    public function calculateRanking(int $tahun): Collection
    {
        // 1. Get all prestasi data for the year
        $prestasiList = PrestasiDosen::with('dosen')
            ->where('tahun', $tahun)
            ->get();
        
        if ($prestasiList->isEmpty()) {
            return collect();
        }
        
        // 2. Get latest AHP weights
        $bobot = $this->getLatestWeights();
        
        if ($bobot->isEmpty()) {
            Log::warning('No AHP weights found. Please calculate AHP first.');
            return collect();
        }
        
        // 3. Get all criteria
        $kriteria = Kriteria::active()->get()->keyBy('kode');
        
        // 4. Normalize data (all criteria are benefit type)
        $normalized = $this->normalizeData($prestasiList, $kriteria);
        
        // 5. Calculate weighted scores
        $results = $this->calculateWeightedScores($normalized, $bobot);
        
        // 6. Sort by score descending
        return $results->sortByDesc('skor_akhir')->values();
    }
    
    /**
     * Get latest AHP weights (one set per criteria)
     * 
     * @return Collection
     */
    public function getLatestWeights(): Collection
    {
        // Get the 4 most recent weights by ID (one per criteria)
        // This ensures we get the latest complete set of 4 kriteria
        $weights = AhpBobot::with('kriteria')
            ->where('is_consistent', true)
            ->orderBy('id', 'desc')
            ->take(4)
            ->get();
        
        if ($weights->count() < 4) {
            Log::warning('Incomplete AHP weights set found. Expected 4, got ' . $weights->count());
            return collect();
        }
        
        return $weights->keyBy('kriteria.kode');
    }
    
    /**
     * Normalize data using max normalization (for benefit criteria)
     * 
     * @param Collection $prestasiList
     * @param Collection $kriteria
     * @return Collection
     */
    protected function normalizeData(Collection $prestasiList, Collection $kriteria): Collection
    {
        // Find max values for each criterion
        $maxValues = [
            'K1' => $prestasiList->max('publikasi') ?: 1,
            'K2' => $prestasiList->max('skor_sinta') ?: 1,
            'K3' => $prestasiList->max('hibah') ?: 1,
            'K4' => $prestasiList->max('buku') ?: 1,
        ];
        
        // Normalize each record
        return $prestasiList->map(function ($prestasi) use ($maxValues) {
            return [
                'dosen_id' => $prestasi->dosen_id,
                'dosen' => $prestasi->dosen,
                'tahun' => $prestasi->tahun,
                'raw_data' => [
                    'publikasi' => $prestasi->publikasi,
                    'skor_sinta' => $prestasi->skor_sinta,
                    'hibah' => $prestasi->hibah,
                    'buku' => $prestasi->buku,
                ],
                'normalized' => [
                    'K1' => $maxValues['K1'] > 0 ? $prestasi->publikasi / $maxValues['K1'] : 0,
                    'K2' => $maxValues['K2'] > 0 ? $prestasi->skor_sinta / $maxValues['K2'] : 0,
                    'K3' => $maxValues['K3'] > 0 ? $prestasi->hibah / $maxValues['K3'] : 0,
                    'K4' => $maxValues['K4'] > 0 ? $prestasi->buku / $maxValues['K4'] : 0,
                ],
                'max_values' => $maxValues,
            ];
        });
    }
    
    /**
     * Calculate weighted scores (SAW formula)
     * 
     * @param Collection $normalized
     * @param Collection $bobot
     * @return Collection
     */
    protected function calculateWeightedScores(Collection $normalized, Collection $bobot): Collection
    {
        return $normalized->map(function ($data) use ($bobot) {
            $skor = 0;
            $detail = [];
            
            foreach (['K1', 'K2', 'K3', 'K4'] as $kode) {
                if (isset($bobot[$kode])) {
                    $nilaiNormalisasi = $data['normalized'][$kode];
                    $bobotKriteria = $bobot[$kode]->bobot;
                    $nilaiBobot = $nilaiNormalisasi * $bobotKriteria;
                    
                    $skor += $nilaiBobot;
                    
                    $detail[$kode] = [
                        'normalized' => round($nilaiNormalisasi, 4),
                        'weight' => round($bobotKriteria, 4),
                        'weighted_score' => round($nilaiBobot, 4),
                    ];
                }
            }
            
            return [
                'dosen_id' => $data['dosen_id'],
                'dosen' => $data['dosen'],
                'tahun' => $data['tahun'],
                'raw_data' => $data['raw_data'],
                'normalized' => $data['normalized'],
                'detail_perhitungan' => $detail,
                'skor_akhir' => round($skor, 4),
                'rank' => 0, // Will be set after sorting
            ];
        });
    }
    
    /**
     * Get ranking with rank numbers
     * 
     * @param int $tahun
     * @return Collection
     */
    public function getRanking(int $tahun): Collection
    {
        $results = $this->calculateRanking($tahun);
        
        // Add rank numbers
        $rank = 1;
        return $results->map(function ($item) use (&$rank) {
            $item['rank'] = $rank++;
            return $item;
        });
    }
    
    /**
     * Get available years from prestasi data
     * 
     * @return Collection
     */
    public function getAvailableYears(): Collection
    {
        return PrestasiDosen::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');
    }
    
    /**
     * Get summary statistics for a year
     * 
     * @param int $tahun
     * @return array
     */
    public function getSummary(int $tahun): array
    {
        $prestasi = PrestasiDosen::where('tahun', $tahun)->get();
        
        if ($prestasi->isEmpty()) {
            return [
                'total_dosen' => 0,
                'avg_publikasi' => 0,
                'avg_skor_sinta' => 0,
                'avg_hibah' => 0,
                'avg_buku' => 0,
                'max_publikasi' => 0,
                'max_skor_sinta' => 0,
                'max_hibah' => 0,
                'max_buku' => 0,
            ];
        }
        
        return [
            'total_dosen' => $prestasi->count(),
            'avg_publikasi' => round($prestasi->avg('publikasi'), 2),
            'avg_skor_sinta' => round($prestasi->avg('skor_sinta'), 2),
            'avg_hibah' => $prestasi->avg('hibah'),
            'avg_buku' => round($prestasi->avg('buku'), 2),
            'max_publikasi' => $prestasi->max('publikasi'),
            'max_skor_sinta' => $prestasi->max('skor_sinta'),
            'max_hibah' => $prestasi->max('hibah'),
            'max_buku' => $prestasi->max('buku'),
        ];
    }
}
