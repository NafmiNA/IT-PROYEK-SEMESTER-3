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

// app/Http/Controllers/Dosen/PenelitianController.php
public function index(Request $request)
{
    $dosen = $request->user()->dosen;              // relasi user->dosen
    $penelitian = \App\Models\Penelitian::where('dosen_id', $dosen->id)
                    ->latest()
                    ->paginate(10);

    return view('dosen.penelitian.index', compact('penelitian'));
}




    public function create()
    {
        // daftar dosen untuk ketua & anggota
        $dosens = Dosen::orderBy('nama')->get(['id', 'nama', 'email']);

        return view('dosen.penelitian.create', compact('dosens'));
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
            // simpan penelitian
            $penelitian = Penelitian::create([
                'judul'       => $validated['judul'],
                'tahun'       => $validated['tahun'],
                'skema'       => $validated['skema']        ?? null,
                'sumber_dana' => $validated['sumber_dana']  ?? null,
                'dana'        => $validated['dana']         ?? null,
                'dosen_id'    => $validated['ketua_id'], // ketua
                'status'      => 'Menunggu',
            ]);

            // buat records pivot: ketua + anggota
            $sync = [];
            $sync[$validated['ketua_id']] = ['peran' => 'Ketua'];

            if (!empty($validated['anggota_id'])) {
                foreach ($validated['anggota_id'] as $anggota) {
                    if ((int) $anggota === (int) $validated['ketua_id']) {
                        continue; // pengaman (sudah divalidasi 'different')
                    }
                    $sync[$anggota] = ['peran' => 'Anggota'];
                }
            }

            // sinkronkan ke pivot penelitian_dosen (pastikan relasi dosens() ada di model Penelitian)
            $penelitian->dosens()->sync($sync);

            // upload dokumentasi (optional) -> SIMPAN KE DISK 'public'
            if ($request->hasFile('dokumentasi')) {
                foreach ($request->file('dokumentasi') as $file) {
                    // folder: storage/app/public/penelitian/{id}/
                    $folder   = "penelitian/{$penelitian->id}";
                    $filename = time() . '_' . $file->getClientOriginalName();

                    // simpan file
                    $path = Storage::disk('public')->putFileAs($folder, $file, $filename);

                    // simpan metadata ke DB (pakai kolom gdrive_path sebagai path lokal)
                    Dokumentasi::create([
                        'penelitian_id' => $penelitian->id,
                        'file_name'     => $file->getClientOriginalName(),
                        'mime'          => $file->getMimeType(),
                        'size'          => $file->getSize(),
                        'gdrive_path'   => $path, // contoh: "penelitian/12/1717xxxx_foto.jpg"
                    ]);
                }
            }
        }); // <= penting: tutup transaction

        return redirect()
            ->route('dosen.penelitian.index')
            ->with('success', 'Penelitian berhasil ditambahkan!');
    }

    public function update(Request $request, Penelitian $penelitian)
    {
        $data = $request->validate([
            'judul'         => 'required|string|max:255',
            'tahun'         => 'required|integer|min:2000|max:2100',
            'skema'         => 'nullable|string|max:100',
            'sumber_dana'   => 'nullable|string|max:100',
            'dana'          => 'nullable|numeric|min:0',
            'tempat_terbit' => 'nullable|string|max:255',
            'ketua_id'      => 'required|exists:dosens,id',
            'anggota_id'    => 'nullable|array',
            'anggota_id.*'  => 'different:ketua_id|exists:dosens,id',
            'dokumentasi'   => 'nullable|array',
            'dokumentasi.*' => 'nullable|image|max:4096',
        ]);

        DB::transaction(function () use ($data, $request, $penelitian) {
            // update penelitian
            $penelitian->update([
                'judul'         => $data['judul'],
                'tahun'         => $data['tahun'],
                'skema'         => $data['skema']         ?? null,
                'sumber_dana'   => $data['sumber_dana']   ?? null,
                'dana'          => $data['dana']          ?? null,
                'tempat_terbit' => $data['tempat_terbit'] ?? null,
                'dosen_id'      => $data['ketua_id'], // update ketua utama juga
            ]);

            // sinkronisasi tim (ketua + anggota)
            $sync = [];
            $sync[$data['ketua_id']] = ['peran' => 'Ketua'];
            if (!empty($data['anggota_id'])) {
                foreach ($data['anggota_id'] as $id) {
                    if ((int) $id === (int) $data['ketua_id']) {
                        continue;
                    }
                    $sync[$id] = ['peran' => 'Anggota'];
                }
            }
            $penelitian->dosens()->sync($sync);

            // tambahan dokumentasi (local disk 'public')
            if ($request->hasFile('dokumentasi')) {
                foreach ($request->file('dokumentasi') as $file) {
                    $folder     = "penelitian/{$penelitian->id}";
                    $filename   = time() . '_' . $file->getClientOriginalName();
                    $storedPath = Storage::disk('public')->putFileAs($folder, $file, $filename);

                    Dokumentasi::create([
                        'penelitian_id' => $penelitian->id,
                        'file_name'     => $file->getClientOriginalName(),
                        'mime'          => $file->getMimeType(),
                        'size'          => $file->getSize(),
                        'gdrive_path'   => $storedPath, // simpan path lokal
                    ]);
                }
            }
        });

        return back()->with('ok', 'Perubahan disimpan');
    }
}
