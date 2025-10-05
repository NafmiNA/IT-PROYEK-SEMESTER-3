<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penelitian extends Model
{
    // kalau nama tabel kamu 'penelitian' (bukan jamak) tulis ini:
    protected $table = 'penelitian';

    // izinkan kolom-kolom yang di-create/update massal
    protected $fillable = [
        'judul',
        'tahun',
        'skema',
        'sumber_dana',
        'dana',
        'status',
        'dosen_id',
    ];

    // opsional: casting angka
    protected $casts = [
        'tahun' => 'integer',
        'dana'  => 'integer',
    ];

    // relasi opsional
    public function dosen()
{
    // many-to-many dengan pivot 'role'
    return $this->belongsToMany(Dosen::class, 'penelitian_dosen', 'penelitian_id', 'dosen_id')
                ->withPivot('role')
                ->withTimestamps();
}

public function ketua()
{
    return $this->dosen()->wherePivot('role', 'ketua');
}

public function anggota()
{
    return $this->belongsToMany(Dosen::class, 'penelitian_dosen')
                ->withPivot('peran')
                ->withTimestamps();
}

public function dokumentasi()
{
    return $this->hasMany(Dokumentasi::class, 'penelitian_id', 'penelitian_id');
}


}
