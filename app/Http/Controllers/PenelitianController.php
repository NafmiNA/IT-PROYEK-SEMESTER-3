<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use Illuminate\Http\Request;

class PenelitianController extends Controller
{
    public function index()
    {
        $penelitians = Penelitian::latest()->get();
        return view('mahasiswa.penelitian.index', compact('penelitians'));
    }

    public function create()
    {
        return view('mahasiswa.penelitian.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'jenis' => 'required|string|max:100',
            'ketua' => 'required|string|max:100',
            'email_ketua' => 'required|email',
            'status' => 'required|string',
            'tahun' => 'required|numeric',
            'dokumen' => 'nullable|file|mimes:pdf,docx|max:2048',
        ]);

        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $folder = 'SIDEPAN/Penelitian/Dokumen';
            $filename = uniqid('', true) . '_' . $file->getClientOriginalName();
            $validated['dokumen'] = \Storage::disk('public')->putFileAs($folder, $file, $filename);
        }

        Penelitian::create($validated);
        return redirect()->route('penelitian.index')->with('success', 'Data penelitian berhasil ditambahkan.');
    }

    public function edit(Penelitian $penelitian)
    {
        return view('mahasiswa.penelitian.edit', compact('penelitian'));
    }

    public function update(Request $request, Penelitian $penelitian)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'jenis' => 'required|string|max:100',
            'ketua' => 'required|string|max:100',
            'email_ketua' => 'required|email',
            'status' => 'required|string',
            'tahun' => 'required|numeric',
            'dokumen' => 'nullable|file|mimes:pdf,docx|max:2048',
        ]);

        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $folder = 'SIDEPAN/Penelitian/Dokumen';
            $filename = uniqid('', true) . '_' . $file->getClientOriginalName();
            $validated['dokumen'] = \Storage::disk('public')->putFileAs($folder, $file, $filename);
        }

        $penelitian->update($validated);
        return redirect()->route('penelitian.index')->with('success', 'Data penelitian berhasil diperbarui.');
    }

    // ⬇️ Tambahkan fungsi upload dokumen di bawah
    public function uploadDokumen(Request $request, Penelitian $penelitian)
    {
        $request->validate([
            'dokumen' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $folder = 'SIDEPAN/Penelitian/Dokumen';
            $filename = uniqid('', true) . '_' . $file->getClientOriginalName();
            $path = \Storage::disk('public')->putFileAs($folder, $file, $filename);
            $penelitian->update(['dokumen' => $path]);
        }

        return redirect()->route('penelitian.index')->with('success', 'Dokumen berhasil diunggah.');
    }
}