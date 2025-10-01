<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verifikasi extends Model
{
    // Paksa nama tabel yang dipakai
    protected $table = 'verifikasi';

    protected $fillable = [
        'dosen_id', 'jenis', 'referensi_id', 'status', 'catatan',
    ];
}
