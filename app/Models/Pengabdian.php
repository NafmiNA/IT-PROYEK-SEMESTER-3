<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

// Pastikan Model Dosen di-import
use App\Models\Dosen; 
// Pastikan Model Mahasiswa di-import (jika belum)
use App\Models\Mahasiswa;
// Pastikan Model Dokumentasi di-import (jika belum)
use App\Models\Dokumentasi;

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

    // Fungsi getTable() Anda tetap dipertahankan
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

    // --- FUNGSI RELASI 'dosen' YANG DITAMBAHKAN ---
    /**
     * Mendapatkan data Dosen ketua pengabdian (sama seperti ketua()).
     * Digunakan oleh Filament Select ->relationship('dosen', 'nama').
     */
    public function dosen() // <-- INI YANG DITAMBAHKAN
    {
        // Relasinya sama dengan fungsi ketua()
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
    // --- SELESAI PENAMBAHAN ---

    /**
     * Relasi ke banyak dosen melalui pivot pengabdian_dosen.
     */
    public function dosens()
    {
        // Pastikan tabel pivot 'pengabdian_dosen' ada
        return $this->belongsToMany(Dosen::class, 'pengabdian_dosen') 
            ->withPivot('peran')
            ->withTimestamps();
    }

    /**
     * Relasi ke dokumentasi pengabdian.
     */
    public function dokumentasi()
    {
        // Pastikan Model Dokumentasi sudah benar
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
        // Pastikan tabel pivot 'pengabdian_mahasiswa' dan Model Mahasiswa ada
        return $this->belongsToMany(Mahasiswa::class, 'pengabdian_mahasiswa', 'pengabdian_id', 'mahasiswa_id')
            ->withPivot('peran')
            ->withTimestamps();
    }
}
