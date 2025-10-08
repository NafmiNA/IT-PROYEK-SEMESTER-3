<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penelitian extends Model
{
    use HasFactory;

    protected $table = 'penelitian';

    protected $fillable = [
        'judul',
        'tahun',
        'skema',
        'sumber_dana',
        'dana',
        'status',
        'dosen_id',
        'laporan_path',
        'link_jurnal',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'dana'  => 'decimal:2',
    ];

    /**
     * Dosen ketua (relasi langsung dari kolom dosen_id).
     */
    public function ketua()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    /**
     * Semua dosen yang terlibat (ketua + anggota).
     */
    public function dosens()
    {
        return $this->belongsToMany(Dosen::class, 'penelitian_dosen', 'penelitian_id', 'dosen_id')
            ->withPivot('peran')
            ->withTimestamps();
    }

    /**
     * Hanya dosen anggota (bukan ketua).
     */
    public function anggota()
    {
        return $this->belongsToMany(Dosen::class, 'penelitian_dosen', 'penelitian_id', 'dosen_id')
            ->wherePivot('peran', 'Anggota')
            ->withTimestamps();
    }

    /**
     * Dokumentasi penelitian (bisa banyak).
     */
    public function dokumentasi()
    {
        return $this->hasMany(Dokumentasi::class, 'penelitian_id');
    }
}
