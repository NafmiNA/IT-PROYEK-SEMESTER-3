<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengabdian extends Model
{
    protected $table = 'pengabdian'; // ganti ke 'pengabdians' jika tabel kamu plural
    protected $fillable = [
        'dosen_id', 'judul', 'tahun', 'bidang', 'sumber_dana', 'dana', 'status',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function dokumentasis()
    {
        return $this->hasMany(Dokumentasi::class, 'pengabdian_id');
    }
}
