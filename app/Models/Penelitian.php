<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penelitian extends Model
{
    protected $fillable = ['judul','skema','tahun','sumber_dana','dana','status','dosen_id'];
    public function dosen(){ return $this->belongsTo(Dosen::class); }
}

