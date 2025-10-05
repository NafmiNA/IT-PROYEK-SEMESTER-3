<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengabdian extends Model
{
    use HasFactory;

    // PENTING: table yang benar
    protected $table = 'pengabdians';

    protected $fillable = [
        'dosen_id','judul','tahun','skema','sumber_dana','dana','status',
    ];

    public function dosen()       { return $this->belongsTo(Dosen::class,'dosen_id'); }
    public function dokumentasi() { return $this->hasMany(Dokumentasi::class,'pengabdian_id'); }
}
