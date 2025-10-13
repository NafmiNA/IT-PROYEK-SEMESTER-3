<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Dokumentasi;
use Illuminate\Support\Facades\Storage;

class DokumentasiController extends Controller
{
    // Lihat semua dokumentasi yang sudah diunggah
    public function index()
    {
        $dokumentasi = Dokumentasi::where('user_id', Auth::id())->latest()->get();
        return view('mahasiswa.dokumentasi.index', compact('dokumentasi'));
    }

    // Form tambah dokumentasi
    public function create()
    {
        return view('mahasiswa.dokumentasi.create');
    }

    // Simpan dokumentasi ke database & folder storage
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,docx,doc,ppt,pptx,png,jpg,jpeg|max:5120',
        ]);

        $path = $request->file('file')->store('dokumentasi', 'public');

        Dokumentasi::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'file_path' => $path,
        ]);

        return redirect()->route('mahasiswa.dokumentasi.index')->with('success', 'Dokumentasi berhasil ditambahkan!');
    }

    // Hapus dokumentasi
    public function destroy($id)
    {
        $dok = Dokumentasi::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        Storage::disk('public')->delete($dok->file_path);
        $dok->delete();

        return back()->with('success', 'Dokumentasi berhasil dihapus.');
    }

    // 🟢 Form edit dokumentasi
    public function edit($id)
    {
        $dok = Dokumentasi::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('mahasiswa.dokumentasi.edit', compact('dok'));
    }

    // 🟢 Simpan hasil edit
    public function update(Request $request, $id)
    {
        $dok = Dokumentasi::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,docx,doc,ppt,pptx,png,jpg,jpeg|max:5120',
        ]);

        // Jika ada file baru, hapus lama dan upload baru
        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($dok->file_path);
            $path = $request->file('file')->store('dokumentasi', 'public');
            $dok->file_path = $path;
        }

        $dok->judul = $request->judul;
        $dok->save();

        return redirect()->route('mahasiswa.dokumentasi.index')->with('success', 'Dokumentasi berhasil diperbarui!');
    }
}