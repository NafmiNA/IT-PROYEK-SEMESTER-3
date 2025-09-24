<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verifikasi extends Model
{
    protected $fillable = ['tanggal','status','catatan','admin_p3m_id','penelitian_id','pengabdian_id','dosen_id'];
    public function dosen(){ return $this->belongsTo(Dosen::class); }
}
