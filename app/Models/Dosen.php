<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dosen extends Model
{
    protected $table = 'dosens';
    protected $fillable = ['nidn','nama','email','status_aktif','user_id'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    public function penelitians(): HasMany { return $this->hasMany(Penelitian::class); }
    public function pengabdians(): HasMany { return $this->hasMany(Pengabdian::class); }
    public function dokumentasis(): HasMany { return $this->hasMany(Dokumentasi::class); }
}
