<?php

namespace App\Policies;

use App\Models\Pengabdian;
use App\Models\User;

class PengabdianPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'dosen' && $user->dosen !== null;
    }

    /**
     * Determine whether the user can view the model.
     * Dosen bisa view jika dia bagian dari tim (Ketua ATAU Anggota)
     */
    public function view(User $user, Pengabdian $pengabdian): bool
    {
        if (!$user->dosen) {
            return false;
        }

        return $pengabdian->dosens()
            ->where('dosen_id', $user->dosen->id)
            ->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'dosen' && $user->dosen !== null;
    }

    /**
     * Determine whether the user can update the model.
     * Hanya KETUA yang bisa update data utama pengabdian
     */
    public function update(User $user, Pengabdian $pengabdian): bool
    {
        if (!$user->dosen) {
            return false;
        }

        return $pengabdian->dosen_id === $user->dosen->id;
    }

    /**
     * Determine whether the user can delete the model.
     * Hanya KETUA yang bisa delete pengabdian
     */
    public function delete(User $user, Pengabdian $pengabdian): bool
    {
        if (!$user->dosen) {
            return false;
        }

        return $pengabdian->dosen_id === $user->dosen->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Pengabdian $pengabdian): bool
    {
        return $this->delete($user, $pengabdian);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Pengabdian $pengabdian): bool
    {
        return $this->delete($user, $pengabdian);
    }

    /**
     * Determine if user is the ketua of pengabdian.
     */
    public function isKetua(User $user, Pengabdian $pengabdian): bool
    {
        if (!$user->dosen) {
            return false;
        }

        return $pengabdian->dosen_id === $user->dosen->id;
    }

    /**
     * Determine if user is anggota (not ketua) of pengabdian.
     */
    public function isAnggota(User $user, Pengabdian $pengabdian): bool
    {
        if (!$user->dosen) {
            return false;
        }

        $pivot = $pengabdian->dosens()
            ->where('dosen_id', $user->dosen->id)
            ->first();

        return $pivot && $pivot->pivot->peran === 'Anggota';
    }

    /**
     * Determine whether user can upload dokumentasi.
     * Semua anggota tim (ketua dan anggota) bisa upload
     */
    public function uploadDokumentasi(User $user, Pengabdian $pengabdian): bool
    {
        if (!$user->dosen) {
            return false;
        }

        return $pengabdian->dosens()
            ->where('dosen_id', $user->dosen->id)
            ->exists();
    }

    /**
     * Determine whether user can delete dokumentasi.
     * Ketua bisa delete semua, anggota hanya bisa delete milik sendiri
     */
    public function deleteDokumentasi(User $user, Pengabdian $pengabdian): bool
    {
        if (!$user->dosen) {
            return false;
        }

        return $pengabdian->dosens()
            ->where('dosen_id', $user->dosen->id)
            ->exists();
    }
}
