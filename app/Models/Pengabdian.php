<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengabdian extends Model
{
    use HasFactory;

    protected $table = 'pengabdians';

    protected $fillable = [
        'dosen_id',
        'judul',
        'tahun',
        'bidang',
        'skema',
        'sumber_dana',
        'dana',
        'status',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'dana'  => 'decimal:2',
    ];

    /**
     * Ketua utama (kolom dosen_id).
     */
    public function ketua()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    /**
     * Relasi ke banyak dosen melalui pivot pengabdian_dosen.
     */
    public function dosens()
    {
        return $this->belongsToMany(Dosen::class, 'pengabdian_dosen')
            ->withPivot('peran')
            ->withTimestamps();
    }

    /**
     * Relasi ke dokumentasi pengabdian.
     */
    public function dokumentasi()
    {
        return $this->hasMany(Dokumentasi::class, 'pengabdian_id');
    }

    /**
     * Dosen yang terlibat selain ketua.
     */
    public function dosenTerlibat()
    {
        return $this->dosens()->wherePivot('peran', 'Anggota');
    }
}
