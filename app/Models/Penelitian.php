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
        return $this->belongsTo(Dosen::class);
    }
}
