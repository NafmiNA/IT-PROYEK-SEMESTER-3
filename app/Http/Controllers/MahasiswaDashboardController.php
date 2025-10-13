<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use App\Models\Dokumentasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MahasiswaDashboardController extends Controller
{
    /**
     * Halaman utama Dashboard Mahasiswa
     */
    public function index()
    {
        $user = Auth::user();
        $profilMahasiswa = Mahasiswa::firstWhere('email', $user?->email);

        $penelitian = Penelitian::with('ketua')->latest()->get();
        $pengabdian = Pengabdian::with('ketua')->latest()->get();
        $dokumentasi = Dokumentasi::latest()->get();

        $penelitianAllowed = [];
        $pengabdianAllowed = [];

        if ($profilMahasiswa) {
            $penelitianAllowed = $profilMahasiswa->penelitians()->pluck('penelitian.id')->all();
            $pengabdianAllowed = $profilMahasiswa->pengabdians()->pluck('pengabdians.id')->all();
        }

        return view('mahasiswa.dashboard', [
            'profilMahasiswa' => $profilMahasiswa,
            'penelitianList'  => $penelitian,
            'pengabdianList'  => $pengabdian,
            'penelitianAllowed' => $penelitianAllowed,
            'pengabdianAllowed' => $pengabdianAllowed,
            'dokumentasi' => $dokumentasi,
        ]);
    }

    /**
     * Tampilkan form tambah dokumentasi
     */
    public function create()
    {
        return view('mahasiswa.dokumentasi.create');
    }

    /**
     * Simpan data dokumentasi baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('dokumentasi', 'public');
        }

        Dokumentasi::create([
            'judul' => $request->judul,
            'file' => $path,
        ]);

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Dokumentasi berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit dokumentasi
     */
    public function edit($id)
    {
        $dokumentasi = Dokumentasi::findOrFail($id);
        return view('mahasiswa.dokumentasi.edit', compact('dokumentasi'));
    }

    /**
     * Update dokumentasi
     */
    public function update(Request $request, $id)
    {
        $dokumentasi = Dokumentasi::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        $path = $dokumentasi->file;
        if ($request->hasFile('file')) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('file')->store('dokumentasi', 'public');
        }

        $dokumentasi->update([
            'judul' => $request->judul,
            'file' => $path,
        ]);

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Dokumentasi berhasil diperbarui!');
    }

    /**
     * Hapus dokumentasi
     */
    public function destroy($id)
    {
        $dokumentasi = Dokumentasi::findOrFail($id);

        if ($dokumentasi->file) {
            Storage::disk('public')->delete($dokumentasi->file);
        }

        $dokumentasi->delete();

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Dokumentasi berhasil dihapus!');
    }
}