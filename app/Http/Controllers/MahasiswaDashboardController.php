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

        // Get only penelitian and pengabdian where student is participating
        if ($profilMahasiswa) {
            $penelitian = $profilMahasiswa->penelitians()->with(['ketua','dokumentasi'])->latest()->get();
            $pengabdian = $profilMahasiswa->pengabdians()->with(['ketua','dokumentasi'])->latest()->get();
        } else {
            $penelitian = collect();
            $pengabdian = collect();
        }

        // Dokumentasi milik mahasiswa ini saja
        $penelitianIds = $penelitian->pluck('id');
        $pengabdianIds = $pengabdian->pluck('id');
        $dokumentasi = Dokumentasi::whereIn('penelitian_id', $penelitianIds)
            ->orWhereIn('pengabdian_id', $pengabdianIds)
            ->latest('dokumentasi_id')
            ->get();

        return view('mahasiswa.dashboard', [
            'profilMahasiswa' => $profilMahasiswa,
            'penelitianList'  => $penelitian,
            'pengabdianList'  => $pengabdian,
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
            $file = $request->file('file');
            $folder = 'SIDOPPAN/Dokumentasi';
            $filename = uniqid('', true) . '_' . $file->getClientOriginalName();
            $path = \Storage::disk('public')->putFileAs($folder, $file, $filename);
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
            $file = $request->file('file');
            $folder = 'SIDOPPAN/Dokumentasi';
            $filename = uniqid('', true) . '_' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs($folder, $file, $filename);
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
