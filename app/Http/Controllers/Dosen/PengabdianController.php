<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Dosen;
use App\Models\Pengabdian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengabdianController extends Controller
{
    public function index(Request $request)
    {
        $dosen = $request->user()->dosen;

        $pengabdian = Pengabdian::with(['ketua'])
            ->whereHas('dosens', function ($query) use ($dosen) {
                $query->where('dosen_id', $dosen->id);
            })
            ->latest()
            ->paginate(10);

        return view('dosen.pengabdian.index', compact('pengabdian'));
    }

    public function create()
    {
        $dosens = Dosen::orderBy('nama')->get(['id', 'nama', 'email']);
        $mahasiswas = \App\Models\Mahasiswa::orderBy('nama')->get(['id','nama','email']);
        [$bidangOptions, $skemaOptions, $sumberDanaOptions] = $this->pengabdianOptions();

        return view('dosen.pengabdian.create', compact('dosens', 'mahasiswas', 'bidangOptions', 'skemaOptions', 'sumberDanaOptions'));
    }

    public function show(Pengabdian $pengabdian)
    {
        $pengabdian->load(['ketua', 'dosenTerlibat', 'dokumentasi', 'mahasiswas']);

        return view('dosen.pengabdian.show', compact('pengabdian'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'         => 'required|string|max:255',
            'tahun'         => 'required|integer',
            'bidang'        => 'nullable|string|max:100',
            'skema'         => 'nullable|string|max:100',
            'sumber_dana'   => 'nullable|string|max:100',
            'dana'          => 'nullable|numeric|min:0',
            'ketua_id'      => 'required|exists:dosens,id',
            'anggota_id'    => 'nullable|array',
            'anggota_id.*'  => 'different:ketua_id|exists:dosens,id',
            'mahasiswa_id'  => 'nullable|array',
            'mahasiswa_id.*'=> 'exists:mahasiswa,id',
            'dokumentasi'   => 'nullable|array',
            'dokumentasi.*' => 'nullable|image|max:4096',
        ]);

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

            // Sinkron mahasiswa pendukung
            if (!empty($data['mahasiswa_id'])) {
                $mSync = [];
                foreach (array_unique($data['mahasiswa_id']) as $mId) {
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

        return redirect()->route('dosen.pengabdian.index')->with('success', 'Pengabdian berhasil ditambahkan.');
    }

    public function edit(Pengabdian $pengabdian)
    {
        $pengabdian->load(['dosens', 'ketua', 'dokumentasi']);
        $dosens = Dosen::orderBy('nama')->get(['id', 'nama', 'email']);
        $mahasiswas = \App\Models\Mahasiswa::orderBy('nama')->get(['id','nama','email']);
        $anggotaTerpilih = $pengabdian->dosens
            ->filter(fn ($d) => optional($d->pivot)->peran === 'Anggota')
            ->pluck('id')
            ->all();
        $mahasiswaTerpilih = $pengabdian->mahasiswas()->pluck('mahasiswa.id')->all();

        [$bidangOptions, $skemaOptions, $sumberDanaOptions] = $this->pengabdianOptions();

        return view('dosen.pengabdian.edit', compact('pengabdian', 'dosens', 'mahasiswas', 'anggotaTerpilih', 'mahasiswaTerpilih', 'bidangOptions', 'skemaOptions', 'sumberDanaOptions'));
    }

    public function update(Request $request, Pengabdian $pengabdian)
    {
        $data = $request->validate([
            'judul'         => 'required|string|max:255',
            'tahun'         => 'required|integer|min:2000|max:2100',
            'bidang'        => 'nullable|string|max:100',
            'skema'         => 'nullable|string|max:100',
            'sumber_dana'   => 'nullable|string|max:100',
            'dana'          => 'nullable|numeric|min:0',
            'ketua_id'      => 'required|exists:dosens,id',
            'anggota_id'    => 'nullable|array',
            'anggota_id.*'  => 'different:ketua_id|exists:dosens,id',
            'mahasiswa_id'  => 'nullable|array',
            'mahasiswa_id.*'=> 'exists:mahasiswa,id',
            'dokumentasi'   => 'nullable|array',
            'dokumentasi.*' => 'nullable|image|max:4096',
            'status'        => 'nullable|in:Draft,Menunggu,Disetujui,Ditolak',
        ]);

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

            // Sinkron mahasiswa pendukung
            $mSync = [];
            if (!empty($data['mahasiswa_id'])) {
                foreach (array_unique($data['mahasiswa_id']) as $mId) {
                    $mSync[$mId] = ['peran' => 'Pendukung'];
                }
            }
            $pengabdian->mahasiswas()->sync($mSync);

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

        return redirect()->route('dosen.pengabdian.show', $pengabdian)->with('ok', 'Perubahan disimpan.');
    }

    public function destroy(Pengabdian $pengabdian)
    {
        DB::transaction(function () use ($pengabdian) {
            foreach ($pengabdian->dokumentasi as $doc) {
                if ($doc->gdrive_path && Storage::disk('public')->exists($doc->gdrive_path)) {
                    Storage::disk('public')->delete($doc->gdrive_path);
                }
                $doc->delete();
            }

            $pengabdian->dosens()->detach();
            $pengabdian->delete();
        });

        return redirect()->route('dosen.pengabdian.index')->with('success', 'Pengabdian berhasil dihapus.');
    }

    private function pengabdianOptions(): array
    {
        $bidangOptions = [
            'Pendidikan',
            'Kesehatan',
            'Ekonomi Kreatif',
            'Teknologi & Informasi',
            'Lingkungan',
            'Sosial Kemasyarakatan',
            'Lainnya',
        ];

        $skemaOptions = [
            'Program Kemitraan Masyarakat (PKM)',
            'Kemitraan Masyarakat',
            'Pengabdian Berbasis Riset',
            'Pengabdian Mandiri',
            'KKN Tematik',
        ];

        $sumberDanaOptions = [
            'DRPM',
            'Kemendikbud',
            'Internal Kampus',
            'Hibah Pemerintah Daerah',
            'Corporate Social Responsibility (CSR)',
            'Mandiri',
            'Lainnya',
        ];

        return [$bidangOptions, $skemaOptions, $sumberDanaOptions];
    }
}
