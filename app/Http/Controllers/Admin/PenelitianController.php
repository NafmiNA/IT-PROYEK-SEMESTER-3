<?php

// MODIFIKASI: Namespace diubah ke Admin
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Dosen;
use App\Models\Penelitian;
use App\Models\Mahasiswa; // <-- Diperbaiki
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class PenelitianController extends Controller
{
    public function index(Request $request)
    {
        // MODIFIKASI: Filter dosen dihapus untuk Admin
        // $dosen = $request->user()->dosen; // <-- Dihapus

        $penelitian = Penelitian::with(['ketua'])
            // MODIFIKASI: Filter whereHas dihapus agar Admin melihat semua
            // ->whereHas('dosens', function ($query) use ($dosen) {
            //     $query->where('dosen_id', $dosen->id);
            // })
            ->latest()
            ->paginate(10);

        // MODIFIKASI: Mengarah ke view admin
        return view('admin.penelitian.index', compact('penelitian'));
    }

    public function create()
    {
        $this->authorize('create', Penelitian::class);

        $dosens = Dosen::orderBy('nama')->get(['id', 'nama', 'email']);
        $mahasiswas = Mahasiswa::orderBy('nama')->get(['id','nama','email']);
        [$skemaOptions, $sumberDanaOptions] = $this->penelitianOptions();

        // MODIFIKASI: Mengarah ke view admin
        return view('admin.penelitian.create', compact('dosens', 'mahasiswas', 'skemaOptions', 'sumberDanaOptions'));
    }

    public function show(Penelitian $penelitian)
    {
        $this->authorize('view', $penelitian);

        $relations = ['ketua', 'dosens', 'dokumentasi'];
        if (Schema::hasTable('penelitian_mahasiswa')) {
            $relations[] = 'mahasiswas';
        }
        $penelitian->load($relations);

        // MODIFIKASI: Admin selalu dianggap sebagai "Ketua" untuk hak akses penuh
        $isKetua = true; 

        // MODIFIKASI: Mengarah ke view admin
        return view('admin.penelitian.show', compact('penelitian', 'isKetua'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Penelitian::class);

        $validated = $request->validate([
            'judul'          => 'required|string|max:255',
            'tahun'          => 'required|integer',
            'skema'          => 'nullable|string|max:255',
            'sumber_dana'    => 'nullable|string|max:255',
            'dana'           => 'nullable|numeric',
            'ketua_id'       => 'required|exists:dosens,id',
            'anggota_id'     => 'nullable|array',
            'anggota_id.*'   => 'different:ketua_id|exists:dosens,id',
            'mahasiswa_id'   => 'nullable|array',
            'mahasiswa_id.*' => 'nullable|exists:mahasiswa,id',
            'link_jurnal'    => 'nullable|url',
            'laporan_jurnal' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        // (Logika DDL dan DB::transaction di bawah ini sudah benar dan tidak perlu diubah)
        $this->ensurePenelitianMahasiswaPivot();

        DB::transaction(function () use ($validated, $request) {
            $createData = [
                'judul'       => $validated['judul'],
                'tahun'       => $validated['tahun'],
                'skema'       => $validated['skema'] ?? null,
                'sumber_dana' => $validated['sumber_dana'] ?? null,
                'dana'        => $validated['dana'] ?? null,
                'dosen_id'    => $validated['ketua_id'],
                'status'      => 'Menunggu', // <-- Menambahkan status default
            ];

            if (Schema::hasColumn('penelitian', 'link_jurnal')) {
                $createData['link_jurnal'] = $validated['link_jurnal'] ?? null;
            }

            $penelitian = Penelitian::create($createData);

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

            if (Schema::hasTable('penelitian_mahasiswa')) {
                $ids = collect($validated['mahasiswa_id'] ?? [])->filter()->unique()->values();
                $mahasiswaSync = [];
                foreach ($ids as $mId) {
                    $mahasiswaSync[$mId] = ['peran' => 'Pendukung'];
                }
                $penelitian->mahasiswas()->sync($mahasiswaSync);
            }
        
            if ($request->hasFile('laporan_jurnal')) {
                $file = $request->file('laporan_jurnal');
                $folder   = "penelitian/laporan/{$penelitian->id}";
                $filename = uniqid('', true) . '_' . $file->getClientOriginalName();
                $path = Storage::disk('public')->putFileAs($folder, $file, $filename);

                if (Schema::hasColumn('penelitian', 'laporan_path')) {
                    $penelitian->update(['laporan_path' => $path]);
                }
            }
        });

        // MODIFIKASI: Redirect ke rute admin
        return redirect()
            ->route('admin.penelitian.index')
            ->with('success', 'Penelitian berhasil disimpan!');
    }

    public function edit(Penelitian $penelitian)
    {
        $this->authorize('update', $penelitian);

        $penelitian->load(['dosens', 'ketua', 'dokumentasi']);
        $dosens = Dosen::orderBy('nama')->get(['id', 'nama', 'email']);
        $mahasiswas = Mahasiswa::orderBy('nama')->get(['id','nama','email']);
        $anggotaTerpilih = $penelitian->dosens
            ->filter(fn ($d) => optional($d->pivot)->peran === 'Anggota')
            ->pluck('id')
            ->all();
        $mahasiswaTerpilih = Schema::hasTable('penelitian_mahasiswa')
            ? $penelitian->mahasiswas()->pluck('mahasiswa.id')->all()
            : [];

        [$skemaOptions, $sumberDanaOptions] = $this->penelitianOptions();

        // MODIFIKASI: Mengarah ke view admin
        return view('admin.penelitian.edit', compact('penelitian', 'dosens', 'mahasiswas', 'anggotaTerpilih', 'mahasiswaTerpilih', 'skemaOptions', 'sumberDanaOptions'));
    }

    public function update(Request $request, Penelitian $penelitian)
    {
        $this->authorize('update', $penelitian);

        $data = $request->validate([
            'judul'          => 'required|string|max:255',
            'tahun'          => 'required|integer|min:2000|max:2100',
            'skema'          => 'nullable|string|max:100',
            'sumber_dana'    => 'nullable|string|max:100',
            'dana'           => 'nullable|numeric|min:0',
            'ketua_id'       => 'required|exists:dosens,id',
            'anggota_id'     => 'nullable|array',
            'anggota_id.*'   => 'different:ketua_id|exists:dosens,id',
            'mahasiswa_id'   => 'nullable|array',
            'mahasiswa_id.*' => 'nullable|exists:mahasiswa,id',
            'link_jurnal'    => 'nullable|url',
            'laporan_jurnal' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'status'         => 'nullable|in:Draft,Menunggu,Disetujui,Ditolak', // Menambahkan validasi status
        ]);

        // (Logika DDL dan DB::transaction di bawah ini sudah benar)
        $this->ensurePenelitianMahasiswaPivot();

        DB::transaction(function () use ($data, $request, $penelitian) {
            $updateData = [
                'judul'       => $data['judul'],
                'tahun'       => $data['tahun'],
                'skema'       => $data['skema'] ?? null,
                'sumber_dana' => $data['sumber_dana'] ?? null,
                'dana'        => $data['dana'] ?? null,
                'dosen_id'    => $data['ketua_id'],
                'status'      => $data['status'] ?? $penelitian->status, // Memperbarui status
            ];

            if (Schema::hasColumn('penelitian', 'link_jurnal')) {
                $updateData['link_jurnal'] = $data['link_jurnal'] ?? $penelitian->link_jurnal;
            }

            $penelitian->update($updateData);

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

            if (Schema::hasTable('penelitian_mahasiswa')) {
                $ids = collect($data['mahasiswa_id'] ?? [])->filter()->unique()->values();
                $mahasiswaSync = [];
                foreach ($ids as $mId) {
                    $mahasiswaSync[$mId] = ['peran' => 'Pendukung'];
                }
                $penelitian->mahasiswas()->sync($mahasiswaSync);
            }

            if ($request->hasFile('laporan_jurnal')) {
                if (Schema::hasColumn('penelitian', 'laporan_path')) {
                    if ($penelitian->laporan_path && Storage::disk('public')->exists($penelitian->laporan_path)) {
                        Storage::disk('public')->delete($penelitian->laporan_path);
                    }
                }

                $file = $request->file('laporan_jurnal');
                $folder   = "penelitian/laporan/{$penelitian->id}";
                $filename = uniqid('', true) . '_' . $file->getClientOriginalName();
                $storedPath = Storage::disk('public')->putFileAs($folder, $file, $filename);

                if (Schema::hasColumn('penelitian', 'laporan_path')) {
                    $penelitian->update(['laporan_path' => $storedPath]);
                }
            }
        });

        // MODIFIKASI: Redirect ke rute admin
        return redirect()->route('admin.penelitian.show', $penelitian)->with('ok', 'Perubahan disimpan!');
    }

    public function destroy(Penelitian $penelitian)
    {
        $this->authorize('delete', $penelitian);

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

        // MODIFIKASI: Redirect ke rute admin
        return redirect()
            ->route('admin.penelitian.index')
            ->with('success', 'Penelitian berhasil dihapus.');
    }

    // ========================================================================
    // FUNGSI BARU DITAMBAHKAN: Untuk tombol Verifikasi
    // ========================================================================
    public function updateStatus(Request $request, Penelitian $penelitian)
    {
        $validated = $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
        ]);

        $penelitian->update([
            'status' => $validated['status']
        ]);

        return redirect()
            ->route('admin.penelitian.index')
            ->with('success', 'Status penelitian berhasil diperbarui!');
    }
    // ========================================================================


    // (Helper function di bawah ini sudah benar dan tidak perlu diubah)
    private function penelitianOptions(): array
    {
        $skemaOptions = [
            'Penelitian Dasar',
            'Penelitian Terapan',
            'Penelitian Pengembangan',
            'Penelitian Mandiri',
            'Penelitian Kemitraan',
        ];

        $sumberDanaOptions = [
            'DRPM',
            'Kemendikbud',
            'Internal Kampus',
            'Hibah Industri',
            'Mandiri',
            'Lainnya',
        ];

        return [$skemaOptions, $sumberDanaOptions];
    }

    private function ensurePenelitianMahasiswaPivot(): void
    {
        if (!Schema::hasTable('penelitian_mahasiswa')) {
            Schema::create('penelitian_mahasiswa', function (Blueprint $table) {
                $table->id();
                $table->foreignId('penelitian_id')->constrained('penelitian')->cascadeOnDelete();
                $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
                $table->string('peran')->default('Pendukung');
                $table->timestamps();
                $table->unique(['penelitian_id','mahasiswa_id']);
            });
        }
    }
}