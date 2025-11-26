<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AhpBobot extends Model
{
    protected $table = 'ahp_bobot';
    
    protected $fillable = [
        'kriteria_id',
        'bobot',
        'consistency_ratio',
        'is_consistent',
        'calculated_at'
    ];
    
    protected $casts = [
        'bobot' => 'decimal:6',
        'consistency_ratio' => 'decimal:6',
        'is_consistent' => 'boolean',
        'calculated_at' => 'datetime',
    ];
    
    /**
     * Get the kriteria
     */
    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class);
    }
    
    /**
     * Get bobot as percentage
     */
    public function getBobotPercentAttribute()
    {
        return round($this->bobot * 100, 2);
    }
}
