<?php

// MODIFIKASI: Namespace diubah ke Admin
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Dosen;
use App\Models\Pengabdian;
use App\Models\Mahasiswa; // <-- Ditambahkan untuk kejelasan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema; // <-- Ditambahkan untuk kejelasan

// -----------------------------------------------------------------
// TAMBAHAN BARU: 'use' untuk fitur Export Excel
// -----------------------------------------------------------------
use App\Exports\PengabdianExport;
use Maatwebsite\Excel\Facades\Excel;
// -----------------------------------------------------------------


class PengabdianController extends Controller
{
    public function index(Request $request)
    {
        // MODIFIKASI: Filter dosen dihapus untuk Admin
        $pengabdian = Pengabdian::with(['ketua'])
            // Filter whereHas dihapus
            ->latest()
            ->paginate(10);

        // MODIFIKASI: Mengarah ke view admin
        return view('admin.pengabdian.index', compact('pengabdian'));
    }

    public function create()
    {
        $this->authorize('create', Pengabdian::class);

        $dosens = Dosen::orderBy('nama')->get(['id', 'nama', 'email']);
        $mahasiswas = Mahasiswa::orderBy('nama')->get(['id','nama','email']);
        [$bidangOptions, $skemaOptions, $sumberDanaOptions] = $this->pengabdianOptions();

        // MODIFIKASI: Mengarah ke view admin
        return view('admin.pengabdian.create', compact('dosens', 'mahasiswas', 'bidangOptions', 'skemaOptions', 'sumberDanaOptions'));
    }

    public function show(Pengabdian $pengabdian)
    {
        $this->authorize('view', $pengabdian);

        $relations = ['ketua', 'dosenTerlibat', 'dokumentasi'];
        if (Schema::hasTable('pengabdian_mahasiswa')) {
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

            if (Schema::hasTable('pengabdian_mahasiswa')) {
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
        $mahasiswas = Mahasiswa::orderBy('nama')->get(['id','nama','email']);
        $anggotaTerpilih = $pengabdian->dosens
            ->filter(fn ($d) => optional($d->pivot)->peran === 'Anggota')
            ->pluck('id')
            ->all();
        $mahasiswaTerpilih = Schema::hasTable('pengabdian_mahasiswa')
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

            if (Schema::hasTable('pengabdian_mahasiswa')) {
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

        // MODIFIKASI: Redirect ke rute admin
        return redirect()->route('admin.pengabdian.show', $pengabdian)->with('ok', 'Perubahan disimpan.');
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

        // MODIFIKASI: Redirect ke rute admin
        return redirect()->route('admin.pengabdian.index')->with('success', 'Pengabdian berhasil dihapus.');
    }

    // ========================================================================
    // TAMBAHAN BARU: Fungsi untuk Export Excel
    // ========================================================================
    public function export() 
    {
        // Panggil Export Class (pastikan file app/Exports/PengabdianExport.php ada)
        // Pastikan Anda sudah meng-install maatwebsite/excel versi 3.1 atau 5.1
        return Excel::download(new PengabdianExport, 'daftar-pengabdian.xlsx');
    }

    // ========================================================================
    // TAMBAHAN BARU: Fungsi untuk tombol Verifikasi
    // ========================================================================
    public function updateStatus(Request $request, Pengabdian $pengabdian)
    {
        $validated = $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
        ]);

        $pengabdian->update([
            'status' => $validated['status']
        ]);

        return redirect()
            ->route('admin.pengabdian.index')
            ->with('success', 'Status pengabdian berhasil diperbarui!');
    }
    // ========================================================================


    // (Helper function di bawah ini sudah benar)
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
        if (!Schema::hasTable('pengabdian_mahasiswa')) {
            Schema::create('pengabdian_mahasiswa', function (Blueprint $table) {
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