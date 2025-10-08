<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';
    protected $fillable = [
        'nama',
        'email',
        'status',
        'tahun',
        'peran'
    ];

    public function penelitians()
    {
        return $this->belongsToMany(Penelitian::class, 'penelitian_mahasiswa', 'mahasiswa_id', 'penelitian_id')
            ->withPivot('peran')
            ->withTimestamps();
    }

    public function pengabdians()
    {
        return $this->belongsToMany(Pengabdian::class, 'pengabdian_mahasiswa', 'mahasiswa_id', 'pengabdian_id')
            ->withPivot('peran')
            ->withTimestamps();
    }
}
