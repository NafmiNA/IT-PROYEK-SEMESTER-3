<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dosen extends Model
{
    protected $table = 'dosens';

    protected $fillable = [
        'nidn',
        'nama',
        'email',
        'status_aktif',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function penelitians(): HasMany
    {
        return $this->hasMany(Penelitian::class);
    }

    public function pengabdians(): BelongsToMany
    {
        return $this->belongsToMany(Pengabdian::class, 'pengabdian_dosen')
            ->withPivot('peran')
            ->withTimestamps();
    }

    public function dokumentasis(): HasMany
    {
        return $this->hasMany(Dokumentasi::class);
    }

    public function penelitian(): BelongsToMany
    {
        return $this->belongsToMany(Penelitian::class, 'penelitian_dosen', 'dosen_id', 'penelitian_id')
            ->withPivot('peran')
            ->withTimestamps();
    }

    public function penelitianKetua(): HasMany
    {
        return $this->hasMany(Penelitian::class, 'dosen_id');
    }

    public function pengabdianKetua(): HasMany
    {
        return $this->hasMany(Pengabdian::class, 'dosen_id');
    }

    public function penelitianAnggota(): BelongsToMany
    {
        return $this->belongsToMany(Penelitian::class, 'penelitian_dosen')
            ->withPivot('peran')
            ->withTimestamps();
    }

    public function pengabdianAnggota(): BelongsToMany
    {
        return $this->belongsToMany(Pengabdian::class, 'pengabdian_dosen')
            ->withPivot('peran')
            ->withTimestamps();
    }

    public function pengabdianDikelola(): HasMany
    {
        return $this->hasMany(Pengabdian::class, 'dosen_id');
    }

    public function pengabdianTerlibat(): BelongsToMany
    {
        return $this->belongsToMany(Pengabdian::class, 'pengabdian_dosen', 'dosen_id', 'pengabdian_id')
            ->withPivot('peran')
            ->withTimestamps();
    }
}
