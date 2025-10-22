<?php

namespace App\Http\Controllers\Dosen;

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
        $this->authorize('create', Pengabdian::class);

        $dosens = Dosen::orderBy('nama')->get(['id', 'nama', 'email']);
        $mahasiswas = \App\Models\Mahasiswa::orderBy('nama')->get(['id','nama','email']);
        [$bidangOptions, $skemaOptions, $sumberDanaOptions] = $this->pengabdianOptions();

        return view('dosen.pengabdian.create', compact('dosens', 'mahasiswas', 'bidangOptions', 'skemaOptions', 'sumberDanaOptions'));
    }

    public function show(Pengabdian $pengabdian)
    {
        $this->authorize('view', $pengabdian);

        $relations = ['ketua', 'dosenTerlibat', 'dokumentasi'];
        if (\Illuminate\Support\Facades\Schema::hasTable('pengabdian_mahasiswa')) {
            $relations[] = 'mahasiswas';
        }
        $pengabdian->load($relations);

        // Check if current user is ketua
        $isKetua = auth()->user()->can('isKetua', $pengabdian);

        return view('dosen.pengabdian.show', compact('pengabdian', 'isKetua'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Pengabdian::class);

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
            'mahasiswa_id.*'=> 'nullable|exists:mahasiswa,id',
            'dokumentasi'   => 'nullable|array',
            'dokumentasi.*' => 'nullable|image|max:4096',
        ]);

        // Ensure pivot exists before wrapping in transaction
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

            // Sinkron mahasiswa pendukung (jika tabel pivot tersedia)
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

        return redirect()->route('dosen.pengabdian.index')->with('success', 'Pengabdian berhasil ditambahkan.');
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

        return view('dosen.pengabdian.edit', compact('pengabdian', 'dosens', 'mahasiswas', 'anggotaTerpilih', 'mahasiswaTerpilih', 'bidangOptions', 'skemaOptions', 'sumberDanaOptions'));
    }

    public function update(Request $request, Pengabdian $pengabdian)
    {
        $this->authorize('update', $pengabdian);

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

        // Ensure pivot exists before wrapping in transaction
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

            // Sinkron mahasiswa pendukung (jika tabel pivot tersedia)
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
        $this->authorize('delete', $pengabdian);

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

    private function ensurePengabdianMahasiswaPivot(): void
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('pengabdian_mahasiswa')) {
            \Illuminate\Support\Facades\Schema::create('pengabdian_mahasiswa', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pengabdian_id')->constrained('pengabdians')->cascadeOnDelete();
                $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
                $table->string('peran')->default('Pendukung');
                $table->timestamps();
                $table->unique(['pengabdian_id','mahasiswa_id']);
            });
        }
    }
}
