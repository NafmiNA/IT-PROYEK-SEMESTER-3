<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penelitian; // pastikan model Penelitian sudah ada

class PenelitianController extends Controller
{
    /**
     * Tampilkan daftar penelitian dosen yang sedang login
     */
    public function index(Request $request)
    {
        $dosen = $request->user()->dosen;           // relasi user->dosen
        $penelitian = Penelitian::where('dosen_id', $dosen->id)
                        ->latest()
                        ->paginate(10);

        return view('dosen.penelitian.index', compact('penelitian'));
    }

    /**
     * Form tambah penelitian
     */
    public function create()
    {
        return view('dosen.penelitian.create');
    }

    /**
     * Simpan penelitian baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'       => 'required|string|max:255',
            'tahun'       => 'required|integer',
            'skema'       => 'nullable|string|max:255',
            'sumber_dana' => 'nullable|string|max:255',
            'dana'        => 'nullable|numeric',
            'status'      => 'required|string|max:50',
        ]);

        $validated['dosen_id'] = $request->user()->dosen->id;

        Penelitian::create($validated);

        return redirect()
            ->route('dosen.penelitian.index')
            ->with('success', 'Penelitian berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail satu penelitian
     */
    public function show(Penelitian $penelitian)
    {
        return view('dosen.penelitian.show', compact('penelitian'));
    }

    /**
     * Form edit penelitian
     */
    public function edit(Penelitian $penelitian)
    {
        return view('dosen.penelitian.edit', compact('penelitian'));
    }

    /**
     * Update data penelitian
     */
    public function update(Request $request, Penelitian $penelitian)
    {
        $validated = $request->validate([
            'judul'       => 'required|string|max:255',
            'tahun'       => 'required|integer',
            'skema'       => 'nullable|string|max:255',
            'sumber_dana' => 'nullable|string|max:255',
            'dana'        => 'nullable|numeric',
            'status'      => 'required|string|max:50',
        ]);

        $penelitian->update($validated);

        return redirect()
            ->route('dosen.penelitian.index')
            ->with('success', 'Penelitian berhasil diperbarui.');
    }

    /**
     * Hapus penelitian
     */
    public function destroy(Penelitian $penelitian)
    {
        $penelitian->delete();

        return redirect()
            ->route('dosen.penelitian.index')
            ->with('success', 'Penelitian berhasil dihapus.');
    }
}
