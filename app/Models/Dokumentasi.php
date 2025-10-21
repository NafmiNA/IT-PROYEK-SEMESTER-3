<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    use HasFactory;

    protected $table = 'dokumentasis';
    protected $primaryKey = 'dokumentasi_id';

    protected $fillable = [
        'penelitian_id',
        'pengabdian_id',
        'file_name',
        'mime',
        'size',
        'gdrive_path',
    ];

    /**
     * Relasi ke Penelitian
     */
    public function penelitian()
    {
        return $this->belongsTo(Penelitian::class, 'penelitian_id');
    }

    /**
     * Relasi ke Pengabdian
     */
    public function pengabdian()
    {
        return $this->belongsTo(Pengabdian::class, 'pengabdian_id');
    }
}