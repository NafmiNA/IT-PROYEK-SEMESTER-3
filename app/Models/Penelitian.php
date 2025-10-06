<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penelitian extends Model
{
    use HasFactory;

    // Jika nama tabel tunggal (bukan jamak), tulis secara eksplisit
    protected $table = 'penelitian';

    // Kolom yang boleh diisi massal
    protected $fillable = [
        'judul',
        'tahun',
        'skema',
        'sumber_dana',
        'dana',
        'status',
        'dosen_id',
    ];

    // Cast otomatis
    protected $casts = [
        'tahun' => 'integer',
        'dana'  => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    /**
     * Dosen ketua (relasi langsung dari kolom dosen_id)
     */
    public function ketua()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    /**
     * Semua dosen yang terlibat (ketua + anggota)
     * Pivot: penelitian_dosen (penelitian_id, dosen_id, peran)
     */
    public function dosens()
    {
        return $this->belongsToMany(Dosen::class, 'penelitian_dosen', 'penelitian_id', 'dosen_id')
                    ->withPivot('peran')
                    ->withTimestamps();
    }

    /**
     * Hanya dosen anggota (bukan ketua)
     */
    public function anggota()
    {
        return $this->belongsToMany(Dosen::class, 'penelitian_dosen', 'penelitian_id', 'dosen_id')
                    ->wherePivot('peran', 'Anggota')
                    ->withTimestamps();
    }

    /**
     * Dokumentasi penelitian (bisa banyak)
     */
    public function dokumentasi()
    {
        return $this->hasMany(Dokumentasi::class, 'penelitian_id');
    }
}
