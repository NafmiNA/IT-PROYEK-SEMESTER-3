<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Dosen;
use App\Models\Penelitian;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
// Library Excel
use App\Exports\PenelitianExport;
use Maatwebsite\Excel\Facades\Excel;

class PenelitianController extends Controller
{
    /**
     * Menampilkan daftar semua penelitian untuk Admin.
     */
    public function index(Request $request)
    {
        // Admin melihat semua data, diurutkan dari yang terbaru
        $penelitian = Penelitian::with(['ketua'])
            ->latest()
            ->paginate(10);

        return view('admin.penelitian.index', compact('penelitian'));
    }

    /**
     * Menampilkan form tambah penelitian baru.
     */
    public function create()
    {
        // Pastikan Policy mengizinkan (atau hapus baris ini jika tidak pakai Policy)
        // $this->authorize('create', Penelitian::class);

        // Ambil data untuk dropdown
        $dosens = Dosen::orderBy('nama')->get(['id', 'nama', 'email']);
        $mahasiswas = Mahasiswa::orderBy('nama')->get(['id','nama','email']);
        
        // Opsi Skema & Sumber Dana
        [$skemaOptions, $sumberDanaOptions] = $this->penelitianOptions();

        return view('admin.penelitian.create', compact('dosens', 'mahasiswas', 'skemaOptions', 'sumberDanaOptions'));
    }

    /**
     * Menyimpan data penelitian baru ke database.
     */
    public function store(Request $request)
    {
        // $this->authorize('create', Penelitian::class);

        // 1. Validasi Input
        $validated = $request->validate([
            'judul'          => 'required|string|max:255',
            'tahun'          => 'required|integer|min:2000|max:2100',
            'skema'          => 'nullable|string|max:255',
            'sumber_dana'    => 'nullable|string|max:255',
            'dana'           => 'nullable|numeric',
            'ketua_id'       => 'required|exists:dosens,id', // Admin wajib memilih ketua
            'anggota_id'     => 'nullable|array',
            'anggota_id.*'   => 'different:ketua_id|exists:dosens,id',
            'mahasiswa_id'   => 'nullable|array',
            'mahasiswa_id.*' => 'nullable|exists:mahasiswa,id',
            'link_jurnal'    => 'nullable|url',
            'laporan_jurnal' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            // Admin bisa langsung set status jika mau, atau default 'Menunggu'/'Disetujui'
            'status'         => 'nullable|in:Draft,Menunggu,Disetujui,Ditolak',
        ]);

        // Helper untuk memastikan tabel pivot ada (opsional jika migrasi sudah benar)
        $this->ensurePenelitianMahasiswaPivot();

        DB::transaction(function () use ($validated, $request) {
            // 2. Siapkan Data Utama
            $createData = [
                'judul'       => $validated['judul'],
                'tahun'       => $validated['tahun'],
                'skema'       => $validated['skema'] ?? null,
                'sumber_dana' => $validated['sumber_dana'] ?? null,
                'dana'        => $validated['dana'] ?? null,
                'dosen_id'    => $validated['ketua_id'], // Set Ketua
                // Jika Admin yang buat, default bisa langsung 'Disetujui' atau sesuai input
                'status'      => $validated['status'] ?? 'Disetujui', 
            ];

            if (Schema::hasColumn('penelitian', 'link_jurnal')) {
                $createData['link_jurnal'] = $validated['link_jurnal'] ?? null;
            }

            // 3. Simpan Penelitian
            $penelitian = Penelitian::create($createData);

            // 4. Sync Dosen (Ketua & Anggota)
            $sync = [];
            // Ketua
            $sync[$validated['ketua_id']] = ['peran' => 'Ketua'];
            
            // Anggota
            if (!empty($validated['anggota_id'])) {
                foreach (array_unique($validated['anggota_id']) as $anggota) {
                    // Hindari duplikasi jika ketua terpilih lagi sebagai anggota
                    if ((int) $anggota === (int) $validated['ketua_id']) {
                        continue;
                    }
                    $sync[$anggota] = ['peran' => 'Anggota'];
                }
            }
            $penelitian->dosens()->sync($sync);

            // 5. Sync Mahasiswa
            if (Schema::hasTable('penelitian_mahasiswa')) {
                $ids = collect($validated['mahasiswa_id'] ?? [])->filter()->unique()->values();
                $mahasiswaSync = [];
                foreach ($ids as $mId) {
                    $mahasiswaSync[$mId] = ['peran' => 'Pendukung'];
                }
                $penelitian->mahasiswas()->sync($mahasiswaSync);
            }
        
            // 6. Upload File Laporan (Jika ada)
            if ($request->hasFile('laporan_jurnal')) {
                $file = $request->file('laporan_jurnal');
                $folder   = "SIDOPPAN/Penelitian/{$penelitian->id}/laporan";
                $filename = uniqid('', true) . '_' . $file->getClientOriginalName();
                $path = Storage::disk('public')->putFileAs($folder, $file, $filename);

                if (Schema::hasColumn('penelitian', 'laporan_path')) {
                    $penelitian->update(['laporan_path' => $path]);
                }
            }
        });

        // 7. Redirect Sukses
        return redirect()
            ->route('admin.penelitian.index')
            ->with('success', 'Penelitian berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail penelitian.
     */
    public function show(Penelitian $penelitian)
    {
        // $this->authorize('view', $penelitian);

        $relations = ['ketua', 'dosens', 'dokumentasi'];
        if (Schema::hasTable('penelitian_mahasiswa')) {
            $relations[] = 'mahasiswas';
        }
        $penelitian->load($relations);

        // Admin dianggap memiliki akses penuh (seperti ketua)
        $isKetua = true; 

        return view('admin.penelitian.show', compact('penelitian', 'isKetua'));
    }

    /**
     * Menampilkan form edit penelitian.
     */
    public function edit(Penelitian $penelitian)
    {
        // $this->authorize('update', $penelitian);

        $penelitian->load(['dosens', 'ketua', 'dokumentasi']);
        $dosens = Dosen::orderBy('nama')->get(['id', 'nama', 'email']);
        $mahasiswas = Mahasiswa::orderBy('nama')->get(['id','nama','email']);
        
        // Ambil ID anggota yang sudah terpilih
        $anggotaTerpilih = $penelitian->dosens
            ->filter(fn ($d) => optional($d->pivot)->peran === 'Anggota')
            ->pluck('id')
            ->all();
            
        // Ambil ID mahasiswa yang sudah terpilih
        $mahasiswaTerpilih = Schema::hasTable('penelitian_mahasiswa')
            ? $penelitian->mahasiswas()->pluck('mahasiswa.id')->all()
            : [];

        [$skemaOptions, $sumberDanaOptions] = $this->penelitianOptions();

        return view('admin.penelitian.edit', compact('penelitian', 'dosens', 'mahasiswas', 'anggotaTerpilih', 'mahasiswaTerpilih', 'skemaOptions', 'sumberDanaOptions'));
    }

    /**
     * Memperbarui data penelitian.
     */
    public function update(Request $request, Penelitian $penelitian)
    {
        // $this->authorize('update', $penelitian);

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
            'status'         => 'nullable|in:Draft,Menunggu,Disetujui,Ditolak',
        ]);

        $this->ensurePenelitianMahasiswaPivot();

        DB::transaction(function () use ($data, $request, $penelitian) {
            // Update Data Utama
            $updateData = [
                'judul'       => $data['judul'],
                'tahun'       => $data['tahun'],
                'skema'       => $data['skema'] ?? null,
                'sumber_dana' => $data['sumber_dana'] ?? null,
                'dana'        => $data['dana'] ?? null,
                'dosen_id'    => $data['ketua_id'],
                'status'      => $data['status'] ?? $penelitian->status,
            ];

            if (Schema::hasColumn('penelitian', 'link_jurnal')) {
                $updateData['link_jurnal'] = $data['link_jurnal'] ?? $penelitian->link_jurnal;
            }

            $penelitian->update($updateData);

            // Sync Dosen
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

            // Sync Mahasiswa
            if (Schema::hasTable('penelitian_mahasiswa')) {
                $ids = collect($data['mahasiswa_id'] ?? [])->filter()->unique()->values();
                $mahasiswaSync = [];
                foreach ($ids as $mId) {
                    $mahasiswaSync[$mId] = ['peran' => 'Pendukung'];
                }
                $penelitian->mahasiswas()->sync($mahasiswaSync);
            }

            // Upload Ulang Laporan
            if ($request->hasFile('laporan_jurnal')) {
                // Hapus file lama jika ada
                if (Schema::hasColumn('penelitian', 'laporan_path')) {
                    if ($penelitian->laporan_path && Storage::disk('public')->exists($penelitian->laporan_path)) {
                        Storage::disk('public')->delete($penelitian->laporan_path);
                    }
                }

                $file = $request->file('laporan_jurnal');
                $folder   = "SIDOPPAN/Penelitian/{$penelitian->id}/laporan";
                $filename = uniqid('', true) . '_' . $file->getClientOriginalName();
                $storedPath = Storage::disk('public')->putFileAs($folder, $file, $filename);

                if (Schema::hasColumn('penelitian', 'laporan_path')) {
                    $penelitian->update(['laporan_path' => $storedPath]);
                }
            }
        });

        return redirect()
            ->route('admin.penelitian.index')
            ->with('success', 'Data penelitian berhasil diperbarui!');
    }

    /**
     * Menghapus data penelitian.
     */
    public function destroy(Penelitian $penelitian)
    {
        // $this->authorize('delete', $penelitian);

        DB::transaction(function () use ($penelitian) {
            // Hapus file dokumentasi
            foreach ($penelitian->dokumentasi as $dok) {
                if ($dok->gdrive_path && Storage::disk('public')->exists($dok->gdrive_path)) {
                    Storage::disk('public')->delete($dok->gdrive_path);
                }
                $dok->delete();
            }

            // Detach relasi dan hapus record
            $penelitian->dosens()->detach();
            $penelitian->delete();
        });

        return redirect()
            ->route('admin.penelitian.index')
            ->with('success', 'Penelitian berhasil dihapus.');
    }

    /**
     * Memperbarui status penelitian (Verifikasi).
     */
    public function updateStatus(Request $request, Penelitian $penelitian)
    {
        $validated = $request->validate([
            'status' => 'required|in:Disetujui,Ditolak,Menunggu,Draft',
        ]);

        $penelitian->update([
            'status' => $validated['status']
        ]);

        return redirect()
            ->route('admin.penelitian.index')
            ->with('success', 'Status penelitian berhasil diperbarui!');
    }

    /**
     * Export data penelitian ke Excel.
     */
    public function export()
    {
        return Excel::download(new PenelitianExport, 'data-penelitian.xlsx');
    }

    /**
     * Helper: Opsi Skema dan Sumber Dana
     */
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

    /**
     * Helper: Pastikan tabel pivot mahasiswa ada (Safety check)
     */
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