<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Pengabdian extends Model
{
    use HasFactory;

    protected $table = 'pengabdians';

    protected $fillable = [
        'dosen_id',
        'judul',
        'tahun',
        'bidang',
        'skema',
        'sumber_dana',
        'dana',
        'status',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'dana'  => 'decimal:2',
    ];

    protected static ?string $resolvedTable = null;

    public function getTable()
    {
        if (static::$resolvedTable) {
            return static::$resolvedTable;
        }

        $defaultTable = parent::getTable();

        if (Schema::hasTable($defaultTable)) {
            static::$resolvedTable = $defaultTable;
            return static::$resolvedTable;
        }

        if (Schema::hasTable('pengabdian')) {
            static::$resolvedTable = 'pengabdian';
            return static::$resolvedTable;
        }

        // fallback to default; subsequent queries will fail loudly if the table truly doesn't exist
        static::$resolvedTable = $defaultTable;
        return static::$resolvedTable;
    }

    /**
     * Ketua utama (kolom dosen_id).
     */
    public function ketua()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    /**
     * Relasi ke banyak dosen melalui pivot pengabdian_dosen.
     */
    public function dosens()
    {
        return $this->belongsToMany(Dosen::class, 'pengabdian_dosen')
            ->withPivot('peran')
            ->withTimestamps();
    }

    /**
     * Relasi ke dokumentasi pengabdian.
     */
    public function dokumentasi()
    {
        return $this->hasMany(Dokumentasi::class, 'pengabdian_id');
    }

    /**
     * Dosen yang terlibat selain ketua.
     */
    public function dosenTerlibat()
    {
        return $this->dosens()->wherePivot('peran', 'Anggota');
    }

    /**
     * Mahasiswa pendukung pengabdian (many-to-many).
     */
    public function mahasiswas()
    {
        return $this->belongsToMany(Mahasiswa::class, 'pengabdian_mahasiswa', 'pengabdian_id', 'mahasiswa_id')
            ->withPivot('peran')
            ->withTimestamps();
    }
}
