<?php

// MODIFIKASI: Namespace diubah ke Admin
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Dosen;
use App\Models\Pengabdian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Schema\Blueprint;

class PengabdianController extends Controller
{
    public function index(Request $request)
    {
        // MODIFIKASI: Filter dosen dihapus untuk Admin
        // $dosen = $request->user()->dosen; // <-- Dihapus

        $pengabdian = Pengabdian::with(['ketua'])
            // MODIFIKASI: Filter whereHas dihapus agar Admin melihat semua
            // ->whereHas('dosens', function ($query) use ($dosen) {
            //     $query->where('dosen_id', $dosen->id);
            // })
            ->latest()
            ->paginate(10);

        // MODIFIKASI: Mengarah ke view admin
        return view('admin.pengabdian.index', compact('pengabdian'));
    }

    public function create()
    {
        $this->authorize('create', Pengabdian::class);

        $dosens = Dosen::orderBy('nama')->get(['id', 'nama', 'email']);
        $mahasiswas = \App\Models\Mahasiswa::orderBy('nama')->get(['id','nama','email']);
        [$bidangOptions, $skemaOptions, $sumberDanaOptions] = $this->pengabdianOptions();

        // MODIFIKASI: Mengarah ke view admin
        return view('admin.pengabdian.create', compact('dosens', 'mahasiswas', 'bidangOptions', 'skemaOptions', 'sumberDanaOptions'));
    }

    public function show(Pengabdian $pengabdian)
    {
        $this->authorize('view', $pengabdian);

        $relations = ['ketua', 'dosenTerlibat', 'dokumentasi'];
        if (\Illuminate\Support\Facades\Schema::hasTable('pengabdian_mahasiswa')) {
            $relations[] = 'mahasiswas';
        }
        $pengabdian->load($relations);

        // MODIFIKASI: Admin selalu dianggap sebagai "Ketua" untuk hak akses penuh
        $isKetua = true;

        // MODIFIKASI: Mengarah ke view admin
        return view('admin.pengabdian.show', compact('pengabdian', 'isKetua'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Pengabdian::class);

        $data = $request->validate([
            'judul'        => 'required|string|max:255',
            'tahun'        => 'required|integer',
            'bidang'       => 'nullable|string|max:100',
            'skema'        => 'nullable|string|max:100',
            'sumber_dana'  => 'nullable|string|max:100',
            'dana'         => 'nullable|numeric|min:0',
            'ketua_id'     => 'required|exists:dosens,id',
            'anggota_id'   => 'nullable|array',
            'anggota_id.*' => 'different:ketua_id|exists:dosens,id',
            'mahasiswa_id' => 'nullable|array',
            'mahasiswa_id.*'=> 'nullable|exists:mahasiswa,id',
            'dokumentasi'  => 'nullable|array',
            'dokumentasi.*' => 'nullable|image|max:4096',
        ]);

        // (Logika DDL dan DB::transaction di bawah ini sudah benar dan tidak perlu diubah)
        $this->ensurePengabdianMahasiswaPivot();

        DB::transaction(function () use ($data, $request) {
            $pengabdian = Pengabdian::create([
                'judul'       => $data['judul'],
                'tahun'       => $data['tahun'],
                'bidang'      => $data['bidang'] ?? null,
                'skema'       => $data['skema'] ?? null,
                'sumber_dana' => $data['sumber_dana'] ?? null,
                'dana'        => $data['dana'] ?? null,
                'status'      => 'Menunggu',
                'dosen_id'    => $data['ketua_id'],
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
            $pengabdian->dosens()->sync($sync);

            if (\Illuminate\Support\Facades\Schema::hasTable('pengabdian_mahasiswa')) {
                $ids = collect($data['mahasiswa_id'] ?? [])->filter()->unique()->values();
                $mSync = [];
                foreach ($ids as $mId) {
                    $mSync[$mId] = ['peran' => 'Pendukung'];
                }
                $pengabdian->mahasiswas()->sync($mSync);
            }

            if ($request->hasFile('dokumentasi')) {
                foreach ((array) $request->file('dokumentasi') as $file) {
                    $folder = "pengabdian/{$pengabdian->id}";
                    $name   = uniqid('', true) . '_' . $file->getClientOriginalName();
                    $path   = Storage::disk('public')->putFileAs($folder, $file, $name);

                    Dokumentasi::create([
                        'pengabdian_id' => $pengabdian->id,
                        'file_name'     => $file->getClientOriginalName(),
                        'mime'          => $file->getMimeType(),
                        'size'          => $file->getSize(),
                        'gdrive_path'   => $path,
                    ]);
                }
            }
        });

        // MODIFIKASI: Redirect ke rute admin
        return redirect()->route('admin.pengabdian.index')->with('success', 'Pengabdian berhasil ditambahkan.');
    }

    public function edit(Pengabdian $pengabdian)
    {
        $this->authorize('update', $pengabdian);

        $pengabdian->load(['dosens', 'ketua', 'dokumentasi']);
        $dosens = Dosen::orderBy('nama')->get(['id', 'nama', 'email']);
        $mahasiswas = \App\Models\Mahasiswa::orderBy('nama')->get(['id','nama','email']);
        $anggotaTerpilih = $pengabdian->dosens
            ->filter(fn ($d) => optional($d->pivot)->peran === 'Anggota')
            ->pluck('id')
            ->all();
        $mahasiswaTerpilih = \Illuminate\Support\Facades\Schema::hasTable('pengabdian_mahasiswa')
            ? $pengabdian->mahasiswas()->pluck('mahasiswa.id')->all()
            : [];

        [$bidangOptions, $skemaOptions, $sumberDanaOptions] = $this->pengabdianOptions();

        // MODIFIKASI: Mengarah ke view admin
        return view('admin.pengabdian.edit', compact('pengabdian', 'dosens', 'mahasiswas', 'anggotaTerpilih', 'mahasiswaTerpilih', 'bidangOptions', 'skemaOptions', 'sumberDanaOptions'));
    }

    public function update(Request $request, Pengabdian $pengabdian)
    {
        $this->authorize('update', $pengabdian);

        $data = $request->validate([
            'judul'        => 'required|string|max:255',
            'tahun'        => 'required|integer|min:2000|max:2100',
            'bidang'       => 'nullable|string|max:100',
            'skema'        => 'nullable|string|max:100',
            'sumber_dana'  => 'nullable|string|max:100',
            'dana'         => 'nullable|numeric|min:0',
            'ketua_id'     => 'required|exists:dosens,id',
            'anggota_id'   => 'nullable|array',
            'anggota_id.*' => 'different:ketua_id|exists:dosens,id',
            'mahasiswa_id' => 'nullable|array',
            'mahasiswa_id.*'=> 'exists:mahasiswa,id',
            'dokumentasi'  => 'nullable|array',
            'dokumentasi.*' => 'nullable|image|max:4096',
            'status'       => 'nullable|in:Draft,Menunggu,Disetujui,Ditolak',
        ]);

        // (Logika DDL dan DB::transaction di bawah ini sudah benar dan tidak perlu diubah)
        $this->ensurePengabdianMahasiswaPivot();

        DB::transaction(function () use ($data, $request, $pengabdian) {
            $pengabdian->update([
                'judul'       => $data['judul'],
                'tahun'       => $data['tahun'],
                'bidang'      => $data['bidang'] ?? null,
                'skema'       => $data['skema'] ?? null,
                'sumber_dana' => $data['sumber_dana'] ?? null,
                'dana'        => $data['dana'] ?? null,
                'dosen_id'    => $data['ketua_id'],
                'status'      => $data['status'] ?? $pengabdian->status,
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
            $pengabdian->dosens()->sync($sync);

            if (\Illuminate\Support\Facades\Schema::hasTable('pengabdian_mahasiswa')) {
                $mSync = [];
                if (!empty($data['mahasiswa_id'])) {
                    foreach (array_unique($data['mahasiswa_id']) as $mId) {
                        $mSync[$mId] = ['peran' => 'Pendukung'];
                    }
                }
                $pengabdian->mahasiswas()->sync($mSync);
            }

            if ($request->hasFile('dokumentasi')) {
                foreach ((array) $request->file('dokumentasi') as $file) {
                    $folder = "pengabdian/{$pengabdian->id}";
                    $name   = uniqid('', true) . '_' .