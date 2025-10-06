<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    protected $table = 'dokumentasi';
    protected $primaryKey = 'dokumentasi_id';

    protected $fillable = [
        'penelitian_id','pengabdian_id','file_name','mime','size','gdrive_path'
    ];

    

    // Dokumen MILIK satu penelitian
    public function penelitian()
    {
        // FK di tabel dokumentasi = penelitian_id, PK di penelitian = id
        return $this->belongsTo(Penelitian::class, 'penelitian_id', 'id');
    }

    // Dokumen MILIK satu pengabdian
    public function pengabdian()
    {
        // FK di tabel dokumentasi = pengabdian_id, PK di pengabdians = id
        return $this->belongsTo(Pengabdian::class, 'pengabdian_id', 'id');
    }
}
