<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AhpPerbandingan extends Model
{
    protected $table = 'ahp_perbandingan';
    
    protected $fillable = [
        'kriteria_a_id',
        'kriteria_b_id',
        'nilai',
        'updated_by'
    ];
    
    protected $casts = [
        'nilai' => 'decimal:4',
    ];
    
    /**
     * Get criteria A
     */
    public function kriteriaA(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_a_id');
    }
    
    /**
     * Get criteria B
     */
    public function kriteriaB(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_b_id');
    }
    
    /**
     * Get user who updated
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
