<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrestasiDosen extends Model
{
    protected $table = 'prestasi_dosen';
    
    protected $fillable = [
        'dosen_id',
        'tahun',
        'publikasi',
        'hibah',
        'skor_sinta',
        'buku',
    ];
    
    protected $casts = [
        'tahun' => 'integer',
        'publikasi' => 'integer',
        'hibah' => 'integer',
        'skor_sinta' => 'integer',
        'buku' => 'integer',
    ];
    
    /**
     * Relationship to Dosen
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }
}
