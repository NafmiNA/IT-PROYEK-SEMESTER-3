<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Dosen;
use App\Models\Pengabdian;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Library Excel
use App\Exports\PengabdianExport;
use Maatwebsite\Excel\Facades\Excel;

class PengabdianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengabdian::with(['ketua']);

        // Server-side filtering by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Server-side searching
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('tahun', 'like', "%{$search}%")
                  ->orWhere('skema', 'like', "%{$search}%");
            });
        }

        $pengabdian = $query->latest()->paginate(10)->withQueryString();

        // Status counts for ALL records
        $statusCounts = [
            'total'     => Pengabdian::count(),
            'draft'     => Pengabdian::where('status', 'Draft')->count(),
            'menunggu'  => Pengabdian::where('status', 'Menunggu')->count(),
            'disetujui' => Pengabdian::where('status', 'Disetujui')->count(),
        ];

        return view('admin.pengabdian.index', compact('pengabdian', 'statusCounts'));
    }

    // --- FITUR TAMBAH (CREATE) ---
    public function create()
    {
        $dosens = Dosen::orderBy('nama')->get(['id', 'nama', 'email']);
        $mahasiswas = Mahasiswa::orderBy('nama')->get(['id','nama','email']);
        
        // Mengambil opsi untuk dropdown
        [$bidangOptions, $skemaOptions, $sumberDanaOptions] = $this->pengabdianOptions();

        return view('admin.pengabdian.create', compact('dosens', 'mahasiswas', 'bidangOptions', 'skemaOptions', 'sumberDanaOptions'));
    }

    // --- FITUR SIMPAN (STORE) ---
    public function store(Request $request)
    {
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
            'mahasiswa_id.*'=> 'nullable|exists:mahasiswa,id',
            // Validasi file laporan/dokumentasi (opsional)
            'laporan_akhir'=> 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'link_jurnal'  => 'nullable|url',
            // Admin bisa langsung set status
            'status'       => 'nullable|in:Draft,Menunggu,Disetujui,Ditolak',
            'dokumentasi'  => 'nullable|array',
            'dokumentasi.*'=> 'nullable|image|max:4096', 
        ]);

        $this->ensurePengabdianMahasiswaPivot();

        try {
            DB::transaction(function () use ($data, $request) {
                // 1. Simpan Data Utama
                $pengabdian = Pengabdian::create([
                    'judul'       => $data['judul'],
                    'tahun'       => $data['tahun'],
                    'bidang'      => $data['bidang'] ?? null,
                    'skema'       => $data['skema'] ?? null,
                    'sumber_dana' => $data['sumber_dana'] ?? null,
                    'dana'        => $data['dana'] ?? null,
                    'dosen_id'    => $data['ketua_id'],
                    // Default status 'Disetujui' jika Admin yang input, atau sesuai inputan form
                    'status'      => $data['status'] ?? 'Disetujui', 
                ]);

                if (Schema::hasColumn('pengabdian', 'link_jurnal') && isset($data['link_jurnal'])) {
                    $pengabdian->update(['link_jurnal' => $data['link_jurnal']]);
                }

                // 2. Sync Dosen (Ketua & Anggota)
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

                // 3. Sync Mahasiswa
                if (Schema::hasTable('pengabdian_mahasiswa')) {
                    $ids = collect($data['mahasiswa_id'] ?? [])->filter()->unique()->values();
                    $mSync = [];
                    foreach ($ids as $mId) {
                        $mSync[$mId] = ['peran' => 'Pendukung'];
                    }
                    $pengabdian->mahasiswas()->sync($mSync);
                }

                // 4. Upload File Laporan Akhir
                if ($request->hasFile('laporan_akhir')) {
                    $file = $request->file('laporan_akhir');
                    $folder = "SIDEPAN/Pengabdian/{$pengabdian->id}/laporan";
                    $name   = uniqid('', true) . '_' . $file->getClientOriginalName();
                    $path   = Storage::disk('public')->putFileAs($folder, $file, $name);

                    // Cek nama kolom di DB (sesuaikan dengan migrasi kamu)
                    $colName = Schema::hasColumn('pengabdian', 'laporan_path') ? 'laporan_path' : 'file_path';
                    if (Schema::hasColumn('pengabdian', $colName)) {
                        $pengabdian->update([$colName => $path]);
                    }
                }
                
                // 5. Upload Dokumentasi (Foto)
                if ($request->hasFile('dokumentasi')) {
                    foreach ((array) $request->file('dokumentasi') as $file) {
                        $folder = "SIDEPAN/Pengabdian/{$pengabdian->id}/dokumentasi";
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

            return redirect()->route('admin.pengabdian.index')->with('success', 'Pengabdian berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Log::error('Admin Pengabdian store failed: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan pengabdian: ' . $e->getMessage()]);
        }
    }

    public function show(Pengabdian $pengabdian)
    {
        $relations = ['ketua', 'dosens', 'dokumentasi'];
        if (Schema::hasTable('pengabdian_mahasiswa')) {
            $relations[] = 'mahasiswas';
        }
        $pengabdian->load($relations);

        $isKetua = true; // Admin dianggap punya akses penuh

        return view('admin.pengabdian.show', compact('pengabdian', 'isKetua'));
    }

    public function edit(Pengabdian $pengabdian)
    {
        $pengabdian->load(['dosens', 'ketua']);
        $dosens = Dosen::orderBy('nama')->get(['id', 'nama', 'email']);
        $mahasiswas = Mahasiswa::orderBy('nama')->get(['id','nama','email']);
        
        // Ambil ID anggota terpilih
        $anggotaTerpilih = $pengabdian->dosens
            ->filter(fn ($d) => optional($d->pivot)->peran === 'Anggota')
            ->pluck('id')
            ->all();
            
        // Ambil ID mahasiswa terpilih
        $mahasiswaTerpilih = Schema::hasTable('pengabdian_mahasiswa')
            ? $pengabdian->mahasiswas()->pluck('mahasiswa.id')->all()
            : [];

        [$bidangOptions, $skemaOptions, $sumberDanaOptions] = $this->pengabdianOptions();

        return view('admin.pengabdian.edit', compact('pengabdian', 'dosens', 'mahasiswas', 'anggotaTerpilih', 'mahasiswaTerpilih', 'bidangOptions', 'skemaOptions', 'sumberDanaOptions'));
    }

    public function update(Request $request, Pengabdian $pengabdian)
    {
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
            'mahasiswa_id.*'=> 'nullable|exists:mahasiswa,id',
            'laporan_akhir'=> 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'link_jurnal'  => 'nullable|url',
            'status'       => 'nullable|in:Draft,Menunggu,Disetujui,Ditolak',
            'dokumentasi'  => 'nullable|array',
            'dokumentasi.*'=> 'nullable|image|max:4096',
        ]);

        $this->ensurePengabdianMahasiswaPivot();

        try {
            DB::transaction(function () use ($data, $request, $pengabdian) {
                $updateData = [
                    'judul'       => $data['judul'],
                    'tahun'       => $data['tahun'],
                    'bidang'      => $data['bidang'] ?? null,
                    'skema'       => $data['skema'] ?? null,
                    'sumber_dana' => $data['sumber_dana'] ?? null,
                    'dana'        => $data['dana'] ?? null,
                    'dosen_id'    => $data['ketua_id'],
                    'status'      => $data['status'] ?? $pengabdian->status,
                ];

                if (Schema::hasColumn('pengabdian', 'link_jurnal')) {
                    $updateData['link_jurnal'] = $data['link_jurnal'] ?? $pengabdian->link_jurnal;
                }

                $pengabdian->update($updateData);

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
                $pengabdian->dosens()->sync($sync);

                // Sync Mahasiswa
                if (Schema::hasTable('pengabdian_mahasiswa')) {
                    $ids = collect($data['mahasiswa_id'] ?? [])->filter()->unique()->values();
                    $mSync = [];
                    foreach ($ids as $mId) {
                        $mSync[$mId] = ['peran' => 'Pendukung'];
                    }
                    $pengabdian->mahasiswas()->sync($mSync);
                }

                // Upload File Laporan Baru
                if ($request->hasFile('laporan_akhir')) {
                    $colName = Schema::hasColumn('pengabdian', 'laporan_path') ? 'laporan_path' : 'file_path';
                    
                    if (Schema::hasColumn('pengabdian', $colName)) {
                        // Hapus file lama
                        if ($pengabdian->$colName && Storage::disk('public')->exists($pengabdian->$colName)) {
                            Storage::disk('public')->delete($pengabdian->$colName);
                        }
                        
                        $file = $request->file('laporan_akhir');
                        $folder = "SIDEPAN/Pengabdian/{$pengabdian->id}/laporan";
                        $name   = uniqid('', true) . '_' . $file->getClientOriginalName();
                        $path   = Storage::disk('public')->putFileAs($folder, $file, $name);
                        
                        $pengabdian->update([$colName => $path]);
                    }
                }

                // Upload Dokumentasi Baru
                if ($request->hasFile('dokumentasi')) {
                    foreach ((array) $request->file('dokumentasi') as $file) {
                        $folder = "SIDEPAN/Pengabdian/{$pengabdian->id}/dokumentasi";
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

            return redirect()->route('admin.pengabdian.index')->with('success', 'Data pengabdian berhasil diperbarui.');
        } catch (\Exception $e) {
            \Log::error('Admin Pengabdian update failed: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Gagal memperbarui pengabdian: ' . $e->getMessage()]);
        }
    }

    public function destroy(Pengabdian $pengabdian)
    {
        DB::transaction(function () use ($pengabdian) {
            // Hapus file dokumentasi
            foreach ($pengabdian->dokumentasi as $dok) {
                if ($dok->gdrive_path && Storage::disk('public')->exists($dok->gdrive_path)) {
                    Storage::disk('public')->delete($dok->gdrive_path);
                }
                $dok->delete();
            }
            // Hapus file laporan utama jika ada
            $colName = Schema::hasColumn('pengabdian', 'laporan_path') ? 'laporan_path' : 'file_path';
            if (Schema::hasColumn('pengabdian', $colName) && $pengabdian->$colName) {
                if (Storage::disk('public')->exists($pengabdian->$colName)) {
                    Storage::disk('public')->delete($pengabdian->$colName);
                }
            }

            $pengabdian->dosens()->detach();
            $pengabdian->delete();
        });

        return redirect()->route('admin.pengabdian.index')->with('success', 'Pengabdian berhasil dihapus.');
    }

    // Fungsi Update Status untuk Tombol Verifikasi di Index
    public function updateStatus(Request $request, Pengabdian $pengabdian)
    {
        $validated = $request->validate([
            'status' => 'required|in:Disetujui,Ditolak,Menunggu,Draft',
        ]);

        $pengabdian->update(['status' => $validated['status']]);

        return redirect()->route('admin.pengabdian.index')->with('success', 'Status pengabdian berhasil diperbarui!');
    }

    // ========================================================================
    // LOGIC EXPORT DENGAN FILTER
    // ========================================================================
    public function export(Request $request)
    {
        // 1. Ambil input filter dari Modal di View
        $tahun = $request->input('tahun');
        $status = $request->input('status');
        $skema = $request->input('skema');

        // 2. Buat nama file yang rapi (cth: data-pengabdian-2025.xlsx)
        $fileName = 'data-pengabdian';
        if ($tahun && $tahun != 'all') $fileName .= '-' . $tahun;
        if ($status && $status != 'all') $fileName .= '-' . $status;
        $fileName .= '.xlsx';

        // 3. Panggil Class Export dan kirimkan data filternya
        return Excel::download(new PengabdianExport($tahun, $status, $skema), $fileName);
    }

    private function pengabdianOptions(): array
    {
        $bidangOptions = [
            'Pendidikan', 'Kesehatan', 'Ekonomi Kreatif', 'Teknologi & Informasi', 
            'Lingkungan', 'Sosial Kemasyarakatan', 'Pariwisata', 'Pertanian', 'Lainnya'
        ];

        $skemaOptions = [
            'Program Kemitraan Masyarakat (PKM)',
            'Program Kemitraan Wilayah (PKW)',
            'Program Pengembangan Desa Mitra (PPDM)',
            'KKN Tematik',
            'Pengabdian Mandiri',
            'Program Pengembangan Kewirausahaan (PPK)',
        ];

        $sumberDanaOptions = [
            'DRPM', 'Kemendikbud', 'Internal Kampus', 
            'Hibah Pemerintah Daerah', 'CSR / Industri', 'Mandiri', 'Lainnya'
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