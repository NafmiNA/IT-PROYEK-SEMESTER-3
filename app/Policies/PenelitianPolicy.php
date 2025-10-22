<?php

namespace App\Policies;

use App\Models\Penelitian;
use App\Models\User;

class PenelitianPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Semua dosen bisa lihat list penelitian mereka sendiri
        return $user->role === 'dosen' && $user->dosen !== null;
    }

    /**
     * Determine whether the user can view the model.
     * Dosen bisa view jika dia bagian dari tim (Ketua ATAU Anggota)
     */
    public function view(User $user, Penelitian $penelitian): bool
    {
        if (!$user->dosen) {
            return false;
        }

        // Cek apakah dosen ini bagian dari tim penelitian
        return $penelitian->dosens()
            ->where('dosen_id', $user->dosen->id)
            ->exists();
    }

    /**
     * Determine whether the user can create models.
     * Semua dosen bisa create penelitian baru
     */
    public function create(User $user): bool
    {
        return $user->role === 'dosen' && $user->dosen !== null;
    }

    /**
     * Determine whether the user can update the model.
     * Hanya KETUA yang bisa update data utama penelitian
     */
    public function update(User $user, Penelitian $penelitian): bool
    {
        if (!$user->dosen) {
            return false;
        }

        // Hanya ketua (pemilik penelitian) yang bisa edit
        return $penelitian->dosen_id === $user->dosen->id;
    }

    /**
     * Determine whether the user can delete the model.
     * Hanya KETUA yang bisa delete penelitian
     */
    public function delete(User $user, Penelitian $penelitian): bool
    {
        if (!$user->dosen) {
            return false;
        }

        // Hanya ketua yang bisa delete
        return $penelitian->dosen_id === $user->dosen->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Penelitian $penelitian): bool
    {
        return $this->delete($user, $penelitian);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Penelitian $penelitian): bool
    {
        return $this->delete($user, $penelitian);
    }

    /**
     * Determine if user is the ketua of penelitian.
     */
    public function isKetua(User $user, Penelitian $penelitian): bool
    {
        if (!$user->dosen) {
            return false;
        }

        return $penelitian->dosen_id === $user->dosen->id;
    }

    /**
     * Determine if user is anggota (not ketua) of penelitian.
     */
    public function isAnggota(User $user, Penelitian $penelitian): bool
    {
        if (!$user->dosen) {
            return false;
        }

        // Cek apakah bagian dari tim
        $pivot = $penelitian->dosens()
            ->where('dosen_id', $user->dosen->id)
            ->first();

        // Anggota = ada di pivot dan peran = 'Anggota'
        return $pivot && $pivot->pivot->peran === 'Anggota';
    }

    /**
     * Determine whether user can upload dokumentasi.
     * Semua anggota tim (ketua dan anggota) bisa upload
     */
    public function uploadDokumentasi(User $user, Penelitian $penelitian): bool
    {
        if (!$user->dosen) {
            return false;
        }

        // Semua anggota tim bisa upload dokumentasi
        return $penelitian->dosens()
            ->where('dosen_id', $user->dosen->id)
            ->exists();
    }

    /**
     * Determine whether user can delete dokumentasi.
     * Ketua bisa delete semua, anggota hanya bisa delete milik sendiri (handled di controller)
     */
    public function deleteDokumentasi(User $user, Penelitian $penelitian): bool
    {
        if (!$user->dosen) {
            return false;
        }

        // Minimal harus anggota tim
        return $penelitian->dosens()
            ->where('dosen_id', $user->dosen->id)
            ->exists();
    }
}
