<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Dosen;
use App\Models\Penelitian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PenelitianController extends Controller
{
    public function index(Request $request)
    {
        $dosen = $request->user()->dosen;

        $penelitian = Penelitian::with(['ketua'])
            ->whereHas('dosens', function ($query) use ($dosen) {
                $query->where('dosen_id', $dosen->id);
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
            'ketua_id'      => 'required|exists:dosens,id',
            'anggota_id'    => 'nullable|array',
            'anggota_id.*'  => 'different:ketua_id|exists:dosens,id',
            'dokumentasi'   => 'nullable|array',
            'dokumentasi.*' => 'nullable|image|max:4096',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $penelitian = Penelitian::create([
                'judul'       => $validated['judul'],
                'tahun'       => $validated['tahun'],
                'skema'       => $validated['skema'] ?? null,
                'sumber_dana' => $validated['sumber_dana'] ?? null,
                'dana'        => $validated['dana'] ?? null,
                'dosen_id'    => $validated['ketua_id'],
                'status'      => 'Menunggu',
            ]);

            $sync = [];
            $sync[$validated['ketua_id']] = ['peran' => 'Ketua'];
            if (!empty($validated['anggota_id'])) {
                foreach (array_unique($validated['anggota_id']) as $anggota) {
                    if ((int) $anggota === (int) $validated['ketua_id']) {
                        continue;
                    }
                    $sync[$anggota] = ['peran' => 'Anggota'];
                }
            }
            $penelitian->dosens()->sync($sync);

            if ($request->hasFile('dokumentasi')) {
                foreach ((array) $request->file('dokumentasi') as $file) {
                    $folder   = "penelitian/{$penelitian->id}";
                    $filename = uniqid('', true) . '_' . $file->getClientOriginalName();

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
        });

        return redirect()
            ->route('dosen.penelitian.index')
            ->with('success', 'Penelitian berhasil disimpan!');
    }

    public function edit(Penelitian $penelitian)
    {
        $penelitian->load(['dosens', 'ketua', 'dokumentasi']);
        $dosens = Dosen::orderBy('nama')->get(['id', 'nama', 'email']);
        $anggotaTerpilih = $penelitian->dosens
            ->filter(fn ($d) => optional($d->pivot)->peran === 'Anggota')
            ->pluck('id')
            ->all();

        return view('dosen.penelitian.edit', compact('penelitian', 'dosens', 'anggotaTerpilih'));
    }

    public function update(Request $request, Penelitian $penelitian)
    {
        $data = $request->validate([
            'judul'         => 'required|string|max:255',
            'tahun'         => 'required|integer|min:2000|max:2100',
            'skema'         => 'nullable|string|max:100',
            'sumber_dana'   => 'nullable|string|max:100',
            'dana'          => 'nullable|numeric|min:0',
            'ketua_id'      => 'required|exists:dosens,id',
            'anggota_id'    => 'nullable|array',
            'anggota_id.*'  => 'different:ketua_id|exists:dosens,id',
            'dokumentasi'   => 'nullable|array',
            'dokumentasi.*' => 'nullable|image|max:4096',
            'status'        => 'nullable|in:Draft,Menunggu,Disetujui,Ditolak',
        ]);

        DB::transaction(function () use ($data, $request, $penelitian) {
            $penelitian->update([
                'judul'       => $data['judul'],
                'tahun'       => $data['tahun'],
                'skema'       => $data['skema'] ?? null,
                'sumber_dana' => $data['sumber_dana'] ?? null,
                'dana'        => $data['dana'] ?? null,
                'dosen_id'    => $data['ketua_id'],
                'status'      => $data['status'] ?? $penelitian->status,
            ]);

            $sync = [];
            $sync[$data['ketua_id']] = ['peran' => 'Ketua'];
            if (!empty($data['anggota_id'])) {
                foreach (array_unique($data['anggota_id']) as $id) {
                    if ((int) $id === (int) $data['ketua_id']) {
                        continue;
                    }
                    $sync[$id] = ['peran' => 'Anggota'];
                }
            }
            $penelitian->dosens()->sync($sync);

            if ($request->hasFile('dokumentasi')) {
                foreach ((array) $request->file('dokumentasi') as $file) {
                    $folder   = "penelitian/{$penelitian->id}";
                    $filename = uniqid('', true) . '_' . $file->getClientOriginalName();

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

        return redirect()->route('dosen.penelitian.show', $penelitian)->with('ok', 'Perubahan disimpan!');
    }

    public function destroy(Penelitian $penelitian)
    {
        DB::transaction(function () use ($penelitian) {
            foreach ($penelitian->dokumentasi as $dok) {
                if ($dok->gdrive_path && Storage::disk('public')->exists($dok->gdrive_path)) {
                    Storage::disk('public')->delete($dok->gdrive_path);
                }
                $dok->delete();
            }

            $penelitian->dosens()->detach();
            $penelitian->delete();
        });

        return redirect()
            ->route('dosen.penelitian.index')
            ->with('success', 'Penelitian berhasil dihapus.');
    }
}
