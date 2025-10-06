<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengabdian extends Model
{
    use HasFactory;

    protected $table = 'pengabdians';

    protected $fillable = [
        'dosen_id',
        'judul',
        'tahun',
        'skema',
        'sumber_dana',
        'dana',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI MODEL
    |--------------------------------------------------------------------------
    */

    // Ketua utama (kolom dosen_id)
    public function ketua()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    // Relasi ke banyak dosen melalui pivot pengabdian_dosen
    public function dosens()
    {
        return $this->belongsToMany(Dosen::class, 'pengabdian_dosen')
                    ->withPivot('peran')
                    ->withTimestamps();
    }

    // Relasi ke dokumentasi
    public function dokumentasi()
    {
        return $this->hasMany(Dokumentasi::class, 'pengabdian_id');
    }

    // (Opsional) untuk menampilkan dosen yang terlibat selain ketua
    public function dosenTerlibat()
    {
        return $this->dosens()->wherePivot('peran', 'Anggota');
    }
}
