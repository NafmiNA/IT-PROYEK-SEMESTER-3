<?php

namespace App\Services;

use App\Models\Kriteria;
use App\Models\AhpPerbandingan;
use App\Models\AhpBobot;
use Illuminate\Support\Facades\DB;

class AhpService
{
    // Random Index untuk uji konsistensi berdasarkan jumlah kriteria
    const RANDOM_INDEX = [
        1 => 0.00,
        2 => 0.00,
        3 => 0.58,
        4 => 0.90,
        5 => 1.12,
        6 => 1.24,
        7 => 1.32,
        8 => 1.41,
        9 => 1.45,
        10 => 1.49,
    ];

    /**
     * Build comparison matrix from database
     * Returns n x n matrix
     */
    public function buildMatrix()
    {
        $kriteria = Kriteria::active()->orderBy('id')->get();
        $n = $kriteria->count();
        
        if ($n < 2) {
            throw new \Exception('Minimal 2 kriteria diperlukan untuk perhitungan AHP');
        }
        
        $matrix = [];
        $kriteriaIds = $kriteria->pluck('id')->toArray();
        
        // Build matrix
        foreach ($kriteriaIds as $i => $idA) {
            $matrix[$i] = [];
            foreach ($kriteriaIds as $j => $idB) {
                if ($i == $j) {
                    // Diagonal = 1 (kriteria vs dirinya sendiri)
                    $matrix[$i][$j] = 1.0;
                } else {
                    // Cari nilai perbandingan dari database
                    $comparison = AhpPerbandingan::where(function($q) use ($idA, $idB) {
                        $q->where('kriteria_a_id', $idA)
                          ->where('kriteria_b_id', $idB);
                    })->orWhere(function($q) use ($idA, $idB) {
                        $q->where('kriteria_a_id', $idB)
                          ->where('kriteria_b_id', $idA);
                    })->first();
                    
                    if ($comparison) {
                        if ($comparison->kriteria_a_id == $idA) {
                            $matrix[$i][$j] = (float) $comparison->nilai;
                        } else {
                            // Kebalikan (reciprocal)
                            $matrix[$i][$j] = 1.0 / (float) $comparison->nilai;
                        }
                    } else {
                        // Belum ada perbandingan, default = 1 (sama penting)
                        $matrix[$i][$j] = 1.0;
                    }
                }
            }
        }
        
        return [
            'matrix' => $matrix,
            'kriteria' => $kriteria,
            'n' => $n
        ];
    }

    /**
     * Normalize matrix
     * Setiap elemen dibagi dengan total kolomnya
     */
    public function normalizeMatrix($matrix, $n)
    {
        $normalized = [];
        
        // Hitung total setiap kolom
        $columnSums = [];
        for ($j = 0; $j < $n; $j++) {
            $sum = 0;
            for ($i = 0; $i < $n; $i++) {
                $sum += $matrix[$i][$j];
            }
            $columnSums[$j] = $sum;
        }
        
        // Normalisasi: setiap elemen dibagi total kolomnya
        for ($i = 0; $i < $n; $i++) {
            $normalized[$i] = [];
            for ($j = 0; $j < $n; $j++) {
                $normalized[$i][$j] = $matrix[$i][$j] / $columnSums[$j];
            }
        }
        
        return $normalized;
    }

    /**
     * Calculate weights (priority vector)
     * Bobot = rata-rata setiap baris
     */
    public function calculateWeights($normalized, $n)
    {
        $weights = [];
        
        for ($i = 0; $i < $n; $i++) {
            $sum = 0;
            for ($j = 0; $j < $n; $j++) {
                $sum += $normalized[$i][$j];
            }
            $weights[$i] = $sum / $n;
        }
        
        return $weights;
    }

    /**
     * Calculate lambda max (eigenvalue)
     */
    public function calculateLambdaMax($matrix, $weights, $n)
    {
        $weightedSum = [];
        
        // Kalikan matrix dengan weights
        for ($i = 0; $i < $n; $i++) {
            $sum = 0;
            for ($j = 0; $j < $n; $j++) {
                $sum += $matrix[$i][$j] * $weights[$j];
            }
            $weightedSum[$i] = $sum;
        }
        
        // Hitung lambda untuk setiap baris
        $lambdas = [];
        for ($i = 0; $i < $n; $i++) {
            $lambdas[$i] = $weightedSum[$i] / $weights[$i];
        }
        
        // Lambda max = rata-rata
        return array_sum($lambdas) / $n;
    }

    /**
     * Calculate Consistency Index (CI)
     */
    public function calculateCI($lambdaMax, $n)
    {
        return ($lambdaMax - $n) / ($n - 1);
    }

    /**
     * Calculate Consistency Ratio (CR)
     * CR < 0.1 = Konsisten
     * CR >= 0.1 = Tidak konsisten (perlu revisi)
     */
    public function calculateCR($ci, $n)
    {
        $ri = self::RANDOM_INDEX[$n] ?? 1.49;
        return $ci / $ri;
    }

    /**
     * Full AHP calculation and save to database
     */
    public function calculate()
    {
        DB::beginTransaction();
        
        try {
            // Step 1: Build matrix
            $data = $this->buildMatrix();
            $matrix = $data['matrix'];
            $kriteria = $data['kriteria'];
            $n = $data['n'];
            
            // Step 2: Normalize matrix
            $normalized = $this->normalizeMatrix($matrix, $n);
            
            // Step 3: Calculate weights
            $weights = $this->calculateWeights($normalized, $n);
            
            // Step 4: Calculate lambda max
            $lambdaMax = $this->calculateLambdaMax($matrix, $weights, $n);
            
            // Step 5: Calculate CI dan CR
            $ci = $this->calculateCI($lambdaMax, $n);
            $cr = $this->calculateCR($ci, $n);
            
            // Step 6: Check consistency
            $isConsistent = $cr < 0.1;
            
            // Step 7: Save to database
            $now = now();
            foreach ($kriteria as $index => $k) {
                AhpBobot::create([
                    'kriteria_id' => $k->id,
                    'bobot' => $weights[$index],
                    'consistency_ratio' => $cr,
                    'is_consistent' => $isConsistent,
                    'calculated_at' => $now
                ]);
            }
            
            DB::commit();
            
            return [
                'success' => true,
                'weights' => $weights,
                'consistency_ratio' => $cr,
                'is_consistent' => $isConsistent,
                'lambda_max' => $lambdaMax,
                'ci' => $ci,
                'kriteria' => $kriteria,
                'matrix' => $matrix,
                'normalized' => $normalized
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get current weights for all active criteria
     */
    public function getCurrentWeights()
    {
        $kriteria = Kriteria::active()
            ->with('bobot')
            ->orderBy('id')
            ->get();
            
        return $kriteria->map(function($k) {
            return [
                'kriteria_id' => $k->id,
                'kode' => $k->kode,
                'nama' => $k->nama,
                'bobot' => $k->bobot->bobot ?? 0,
                'bobot_percent' => $k->bobot ? $k->bobot->bobot_percent : 0,
                'is_consistent' => $k->bobot->is_consistent ?? false,
                'consistency_ratio' => $k->bobot->consistency_ratio ?? null,
            ];
        });
    }

    /**
     * Save or update comparison
     */
    public function saveComparison($kriteriaAId, $kriteriaBId, $nilai, $userId)
    {
        // Pastikan A selalu lebih kecil dari B untuk konsistensi
        if ($kriteriaAId > $kriteriaBId) {
            [$kriteriaAId, $kriteriaBId] = [$kriteriaBId, $kriteriaAId];
            $nilai = 1 / $nilai; // Reciprocal
        }
        
        AhpPerbandingan::updateOrCreate(
            [
                'kriteria_a_id' => $kriteriaAId,
                'kriteria_b_id' => $kriteriaBId
            ],
            [
                'nilai' => $nilai,
                'updated_by' => $userId
            ]
        );
    }
}
