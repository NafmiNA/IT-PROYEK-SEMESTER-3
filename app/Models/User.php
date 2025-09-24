<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang bisa diisi secara mass-assignment.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Kolom yang harus disembunyikan ketika diserialisasi.
     *
     * @var array<int,string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tipe casting atribut.
     *
     * @return array<string,string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi satu-satu ke Dosen.
     *
     * Pastikan kolom foreign key di tabel `dosens` bernama `user_id`.
     */
    public function dosen(): HasOne
    {
        return $this->hasOne(Dosen::class, 'user_id');
    }

    /**
     * (Opsional) contoh relasi jika nanti
     * user memiliki banyak penelitian.
     */
    // public function penelitians(): HasMany
    // {
    //     return $this->hasMany(Penelitian::class);
    // }
}
