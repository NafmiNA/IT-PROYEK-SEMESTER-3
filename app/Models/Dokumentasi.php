<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    protected $table = 'dokumentasi'; // ganti ke 'dokumentasis' jika tabel kamu plural
    protected $fillable = [
        'file_path','jenis','tanggal','keterangan',
        'mahasiswa_id','penelitian_id','pengabdian_id','dosen_id'
    ];

    public function dosen()       { return $this->belongsTo(Dosen::class); }
    public function penelitian()  { return $this->belongsTo(Penelitian::class); }
    public function pengabdian()  { return $this->belongsTo(Pengabdian::class); }
}
