<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Penelitian;
use App\Models\Dosen;
use App\Models\Dokumentasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PenelitianController extends Controller
{
    public function index(Request $request)
    {
        $dosen = $request->user()->dosen;

        $penelitian = Penelitian::query()
            ->where('dosen_id', $dosen->id)
            ->orWhereHas('dosens', function ($q) use ($dosen) {
                $q->where('dosen_id', $dosen->id);
            })
            ->latest()
            ->paginate(10);

        return view('dosen.penelitian.index', compact('penelitian'));
    }

    public function create()
    {
        $dosens = Dosen::orderBy('nama')->get(['id', 'nama', 'email']);
        return view('dosen.penelitian.create', compact('dosens'));
    }

    public function show(Penelitian $penelitian)
{
    // Muat relasi biar efisien (ketua, anggota, dokumentasi)
    $penelitian->load(['ketua', 'dosens', 'dokumentasi']);

    return view('dosen.penelitian.show', compact('penelitian'));
}


    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'         => 'required|string|max:255',
            'tahun'         => 'required|integer',
            'skema'         => 'nullable|string|max:255',
            'sumber_dana'   => 'nullable|string|max:255',
            'dana'          => 'nullable|numeric',
            'ketua_id'      => 'required|exists:dosen,id',  // ✅ perbaikan disini
            'anggota_id'    => 'nullable|array',
            'anggota_id.*'  => 'different:ketua_id|exists:dosen,id',
            'dokumentasi'   => 'nullable|array',
            'dokumentasi.*' => 'nullable|image|max:4096',
        ]);

        DB::transaction(function () use ($validated, $request) {

            // simpan penelitian
            $penelitian = Penelitian::create([
                'judul'       => $validated['judul'],
                'tahun'       => $validated['tahun'],
                'skema'       => $validated['skema'] ?? null,
                'sumber_dana' => $validated['sumber_dana'] ?? null,
                'dana'        => $validated['dana'] ?? null,
                'dosen_id'    => $validated['ketua_id'],
                'status'      => 'Menunggu',
            ]);

            // sinkronisasi dosen (ketua + anggota)
            $sync = [];
            $sync[$validated['ketua_id']] = ['peran' => 'Ketua'];
            if (!empty($validated['anggota_id'])) {
                foreach ($validated['anggota_id'] as $anggota) {
                    $sync[$anggota] = ['peran' => 'Anggota'];
                }
            }
            $penelitian->dosens()->sync($sync);

            // upload dokumentasi
            if ($request->hasFile('dokumentasi')) {
                foreach ($request->file('dokumentasi') as $file) {
                    $folder   = "penelitian/{$penelitian->id}";
                    $filename = time().'_'.$file->getClientOriginalName();

                    $path = Storage::disk('public')->putFileAs($folder, $file, $filename);

                    Dokumentasi::create([
                        'penelitian_id' => $penelitian->id,
                        'file_name'     => $file->getClientOriginalName(),
                        'mime'          => $file->getMimeType(),
                        'size'          => $file->getSize(),
                        'gdrive_path'   => $path,
                    ]);
                }
            }

            return $penelitian; // ✅ return agar transaksinya bersih
        });

        return redirect()
            ->route('dosen.penelitian.index')
            ->with('success', 'Penelitian berhasil disimpan!');
    }

    public function update(Request $request, Penelitian $penelitian)
    {
        $data = $request->validate([
            'judul'         => 'required|string|max:255',
            'tahun'         => 'required|integer|min:2000|max:2100',
            'skema'         => 'nullable|string|max:100',
            'sumber_dana'   => 'nullable|string|max:100',
            'dana'          => 'nullable|numeric|min:0',
            'ketua_id'      => 'required|exists:dosen,id',  // ✅ konsisten
            'anggota_id'    => 'nullable|array',
            'anggota_id.*'  => 'different:ketua_id|exists:dosen,id',
            'dokumentasi'   => 'nullable|array',
            'dokumentasi.*' => 'nullable|image|max:4096',
        ]);

        DB::transaction(function () use ($data, $request, $penelitian) {
            $penelitian->update([
                'judul'       => $data['judul'],
                'tahun'       => $data['tahun'],
                'skema'       => $data['skema'] ?? null,
                'sumber_dana' => $data['sumber_dana'] ?? null,
                'dana'        => $data['dana'] ?? null,
                'dosen_id'    => $data['ketua_id'],
            ]);

            $sync = [];
            $sync[$data['ketua_id']] = ['peran' => 'Ketua'];
            if (!empty($data['anggota_id'])) {
                foreach ($data['anggota_id'] as $id) {
                    if ((int)$id === (int)$data['ketua_id']) continue;
                    $sync[$id] = ['peran' => 'Anggota'];
                }
            }
            $penelitian->dosens()->sync($sync);

            if ($request->hasFile('dokumentasi')) {
                foreach ($request->file('dokumentasi') as $file) {
                    $folder   = "penelitian/{$penelitian->id}";
                    $filename = time().'_'.$file->getClientOriginalName();

                    $storedPath = Storage::disk('public')->putFileAs($folder, $file, $filename);

                    Dokumentasi::create([
                        'penelitian_id' => $penelitian->id,
                        'file_name'     => $file->getClientOriginalName(),
                        'mime'          => $file->getMimeType(),
                        'size'          => $file->getSize(),
                        'gdrive_path'   => $storedPath,
                    ]);
                }
            }
        });

        return back()->with('ok', 'Perubahan disimpan!');
    }
}
