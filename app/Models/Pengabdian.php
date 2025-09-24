<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengabdian extends Model
{
    protected $fillable = ['judul','bidang','tahun','dana','status','dosen_id'];
    public function dosen(){ return $this->belongsTo(Dosen::class); }
}
