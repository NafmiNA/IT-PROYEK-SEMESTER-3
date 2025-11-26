<?php

namespace App\Http\Controllers;

use App\Models\Dokumentasi;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswa = Dokumentasi::all();
        return view('mahasiswa.index', compact('mahasiswa'));
    }

    public function create()
    {
        return view('mahasiswa.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'status' => 'required',
            'tahun' => 'required',
            'peran' => 'required',
            'file' => 'nullable|file|mimes:pdf,docx,png,jpg|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $folder = 'SIDOPPAN/Dokumentasi';
            $filename = uniqid('', true) . '_' . $file->getClientOriginalName();
            $validated['file'] = \Storage::disk('public')->putFileAs($folder, $file, $filename);
        }

        Dokumentasi::create($validated);
        return redirect()->route('mahasiswa.index')->with('success', 'Dokumentasi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $mhs = Dokumentasi::findOrFail($id);
        return view('mahasiswa.edit', compact('mhs'));
    }

    public function update(Request $request, $id)
    {
        $mhs = Dokumentasi::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'status' => 'required',
            'tahun' => 'required',
            'peran' => 'required',
            'file' => 'nullable|file|mimes:pdf,docx,png,jpg|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $folder = 'SIDOPPAN/Dokumentasi';
            $filename = uniqid('', true) . '_' . $file->getClientOriginalName();
            $validated['file'] = \Storage::disk('public')->putFileAs($folder, $file, $filename);
        }

        $mhs->update($validated);
        return redirect()->route('mahasiswa.index')->with('success', 'Dokumentasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $mhs = Dokumentasi::findOrFail($id);
        $mhs->delete();
        return redirect()->route('mahasiswa.index')->with('success', 'Dokumentasi berhasil dihapus.');
    }
}