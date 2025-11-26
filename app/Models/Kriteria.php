<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Kriteria extends Model
{
    protected $table = 'kriteria';
    
    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'tipe',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    /**
     * Get comparisons where this criteria is A
     */
    public function perbandinganAsA(): HasMany
    {
        return $this->hasMany(AhpPerbandingan::class, 'kriteria_a_id');
    }
    
    /**
     * Get comparisons where this criteria is B
     */
    public function perbandinganAsB(): HasMany
    {
        return $this->hasMany(AhpPerbandingan::class, 'kriteria_b_id');
    }
    
    /**
     * Get current weight
     */
    public function bobot(): HasOne
    {
        return $this->hasOne(AhpBobot::class)->latestOfMany('calculated_at');
    }
    
    /**
     * Get weight history
     */
    public function bobotHistory(): HasMany
    {
        return $this->hasMany(AhpBobot::class)->orderBy('calculated_at', 'desc');
    }
    
    /**
     * Scope: Only active criteria
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
