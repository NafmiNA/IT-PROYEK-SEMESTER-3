<?php

// app/Http/Controllers/Dosen/PengabdianController.php
namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pengabdian;
use App\Models\Dosen;
use App\Models\Dokumentasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengabdianController extends Controller
{
    public function index(Request $request)
    {
        $dosen = $request->user()->dosen;

        // Ambil semua data pengabdian milik dosen
        $pengabdian = Pengabdian::where('dosen_id', $dosen->id)->get();

        return view('dosen.pengabdian.index', compact('pengabdian'));
    }

    public function create()
    {
        $dosens = Dosen::orderBy('nama')->get(['id','nama','email']);
        return view('dosen.pengabdian.create', compact('dosens'));
    }

 
public function show(Pengabdian $pengabdian)
{
    // muat relasi yang dibutuhkan
    $pengabdian->load(['ketua', 'dosenTerlibat', 'dokumentasi']);

    return view('dosen.pengabdian.show', compact('pengabdian'));
}

    public function store(Request $r)
    {
        $data = $r->validate([
            'judul'         => 'required|string|max:255',
            'tahun'         => 'required|integer',
            'bidang'        => 'nullable|string|max:100',
            'dana'          => 'nullable|numeric|min:0',
            'ketua_id'      => 'required|exists:dosens,id',
            'anggota_id'    => 'nullable|array',
            'anggota_id.*'  => 'different:ketua_id|exists:dosens,id',
            'dokumentasi'   => 'nullable|array',
            'dokumentasi.*' => 'nullable|image|max:4096',
        ]);

        DB::transaction(function() use ($data, $r) {
            // buat record pengabdian (status default Menunggu)
            $pengabdian = Pengabdian::create([
                'judul'     => $data['judul'],
                'tahun'     => $data['tahun'],
                'bidang'    => $data['bidang'] ?? null,
                'dana'      => $data['dana']   ?? null,
                'status'    => 'Menunggu',
                'dosen_id'  => $data['ketua_id'],  // ketua utama
            ]);

            // sinkron tim ke pivot
            $sync = [];
            $sync[$data['ketua_id']] = ['peran' => 'Ketua'];
            if (!empty($data['anggota_id'])) {
                foreach ($data['anggota_id'] as $id) {
                    if ((int)$id === (int)$data['ketua_id']) continue;
                    $sync[$id] = ['peran' => 'Anggota'];
                }
            }
            $pengabdian->dosens()->sync($sync);

            // upload dokumentasi (disk 'public')
            if ($r->hasFile('dokumentasi')) {
                foreach ($r->file('dokumentasi') as $file) {
                    $folder = "pengabdian/{$pengabdian->id}";
                    $name   = time().'_'.$file->getClientOriginalName();
                    $path   = Storage::disk('public')->putFileAs($folder, $file, $name);

                    Dokumentasi::create([
                        'pengabdian_id' => $pengabdian->id,
                        'file_name'     => $file->getClientOriginalName(),
                        'mime'          => $file->getMimeType(),
                        'size'          => $file->getSize(),
                        'gdrive_path'   => $path, // dipakai sebagai path lokal
                    ]);
                }
            }
        });

        return redirect()->route('dosen.pengabdian.index')->with('success','Pengabdian berhasil ditambahkan.');
    }

    public function edit(Pengabdian $pengabdian)
    {
        $dosens = Dosen::orderBy('nama')->get(['id','nama','email']);
        return view('dosen.pengabdian.edit', compact('pengabdian','dosens'));
    }

    public function update(Request $r, Pengabdian $pengabdian)
    {
        $data = $r->validate([
            'judul'         => 'required|string|max:255',
            'tahun'         => 'required|integer|min:2000|max:2100',
            'bidang'        => 'nullable|string|max:100',
            'dana'          => 'nullable|numeric|min:0',
            'ketua_id'      => 'required|exists:dosens,id',
            'anggota_id'    => 'nullable|array',
            'anggota_id.*'  => 'different:ketua_id|exists:dosens,id',
            'dokumentasi'   => 'nullable|array',
            'dokumentasi.*' => 'nullable|image|max:4096',
        ]);

        DB::transaction(function() use ($data, $r, $pengabdian) {
            $pengabdian->update([
                'judul'    => $data['judul'],
                'tahun'    => $data['tahun'],
                'bidang'   => $data['bidang'] ?? null,
                'dana'     => $data['dana']   ?? null,
                'dosen_id' => $data['ketua_id'],
            ]);

            $sync = [];
            $sync[$data['ketua_id']] = ['peran' => 'Ketua'];
            if (!empty($data['anggota_id'])) {
                foreach ($data['anggota_id'] as $id) {
                    if ((int)$id === (int)$data['ketua_id']) continue;
                    $sync[$id] = ['peran' => 'Anggota'];
                }
            }
            $pengabdian->dosens()->sync($sync);

            if ($r->hasFile('dokumentasi')) {
                foreach ($r->file('dokumentasi') as $file) {
                    $folder = "pengabdian/{$pengabdian->id}";
                    $name   = time().'_'.$file->getClientOriginalName();
                    $path   = Storage::disk('public')->putFileAs($folder, $file, $name);

                    Dokumentasi::create([
                        'pengabdian_id' => $pengabdian->id,
                        'file_name'     => $file->getClientOriginalName(),
                        'mime'          => $file->getClientMimeType(),
                        'size'          => $file->getSize(),
                        'gdrive_path'   => $path,
                    ]);
                }
            }
        });

        

        return back()->with('ok','Perubahan disimpan.');
    }
}

