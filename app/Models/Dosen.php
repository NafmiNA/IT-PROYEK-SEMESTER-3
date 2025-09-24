<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dosen extends Model
{
    /**
     * Nama tabel yang direpresentasikan model.
     *
     * @var string
     */
    protected $table = 'dosens';

    /**
     * Kolom yang boleh diisi secara mass-assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nidn',
        'nama',
        'email',
        'status_aktif',
        'user_id',
    ];

    /**
     * Relasi ke model User (dosen dimiliki oleh satu user).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Penelitian (satu dosen banyak penelitian).
     */
    public function penelitians(): HasMany
    {
        return $this->hasMany(Penelitian::class);
    }

    /**
     * Relasi ke Pengabdian (satu dosen banyak pengabdian).
     */
    public function pengabdians(): HasMany
    {
        return $this->hasMany(Pengabdian::class);
    }

    /**
     * Relasi ke Dokumentasi (satu dosen banyak dokumentasi).
     */
    public function dokumentasis(): HasMany
    {
        return $this->hasMany(Dokumentasi::class);
    }
}
