<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use App\Models\Dokumentasi;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MahasiswaDashboardController extends Controller
{
    protected $googleDrive;

    public function __construct(GoogleDriveService $googleDrive)
    {
        $this->googleDrive = $googleDrive;
    }
    /**
     * Halaman utama Dashboard Mahasiswa
     */
    public function index()
    {
        $user = Auth::user();
        $profilMahasiswa = Mahasiswa::firstWhere('email', $user?->email);

        // Get only penelitian and pengabdian where student is participating
        if ($profilMahasiswa) {
            $penelitian = $profilMahasiswa->penelitians()->with(['ketua','dokumentasi'])->latest()->get();
            $pengabdian = $profilMahasiswa->pengabdians()->with(['ketua','dokumentasi'])->latest()->get();
        } else {
            $penelitian = collect();
            $pengabdian = collect();
        }

        // Dokumentasi milik mahasiswa ini saja
        $penelitianIds = $penelitian->pluck('id');
        $pengabdianIds = $pengabdian->pluck('id');
        $dokumentasi = Dokumentasi::whereIn('penelitian_id', $penelitianIds)
            ->orWhereIn('pengabdian_id', $pengabdianIds)
            ->latest('dokumentasi_id')
            ->get();

        return view('mahasiswa.dashboard', [
            'profilMahasiswa' => $profilMahasiswa,
            'penelitianList'  => $penelitian,
            'pengabdianList'  => $pengabdian,
            'dokumentasi' => $dokumentasi,
        ]);
    }

    /**
     * Tampilkan form tambah dokumentasi
     */
    public function create()
    {
        return view('mahasiswa.dokumentasi.create');
    }

    /**
     * Simpan data dokumentasi baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        $path = null;
        $driveFileId = null;
        $driveFileUrl = null;
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $folder = 'SIDOPPAN/Dokumentasi';
            $filename = uniqid('', true) . '_' . $file->getClientOriginalName();
            $path = \Storage::disk('public')->putFileAs($folder, $file, $filename);
            
            Log::info('Mahasiswa standalone dokumentasi uploaded to local storage', ['file' => $file->getClientOriginalName(), 'path' => $path]);
            
            // Try to upload to Google Drive
            if ($this->googleDrive && $this->googleDrive->isConfigured()) {
                try {
                    $folderId = $this->googleDrive->getFolderIdByType('dokumentasi');
                    
                    if ($folderId) {
                        $uploadResult = $this->googleDrive->uploadFile(
                            storage_path('app/public/' . $path),
                            $file->getClientOriginalName(),
                            $folderId
                        );
                        
                        if ($uploadResult) {
                            $driveFileId = $uploadResult['file_id'];
                            $driveFileUrl = $uploadResult['file_url'];
                            Log::info('File uploaded to Google Drive successfully', ['file_id' => $driveFileId]);
                        }
                    } else {
                        Log::warning('Google Drive folder ID not found for dokumentasi');
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to upload to Google Drive', ['error' => $e->getMessage()]);
                }
            } else {
                Log::warning('Google Drive not configured');
            }
        }

        Dokumentasi::create([
            'judul' => $request->judul,
            'file' => $path,
            'drive_file_id' => $driveFileId,
            'drive_file_url' => $driveFileUrl,
            'uploaded_to_drive' => !empty($driveFileId),
        ]);

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Dokumentasi berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit dokumentasi
     */
    public function edit($id)
    {
        $dokumentasi = Dokumentasi::findOrFail($id);
        return view('mahasiswa.dokumentasi.edit', compact('dokumentasi'));
    }

    /**
     * Update dokumentasi
     */
    public function update(Request $request, $id)
    {
        $dokumentasi = Dokumentasi::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        $path = $dokumentasi->file;
        $driveFileId = $dokumentasi->drive_file_id;
        $driveFileUrl = $dokumentasi->drive_file_url;
        
        if ($request->hasFile('file')) {
            // Delete old file from local storage
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            
            // Delete old file from Google Drive
            if ($driveFileId && $this->googleDrive) {
                try {
                    $this->googleDrive->deleteFile($driveFileId);
                    Log::info('Old file deleted from Google Drive', ['file_id' => $driveFileId]);
                } catch (\Exception $e) {
                    Log::warning('Failed to delete old file from Google Drive', ['error' => $e->getMessage()]);
                }
            }
            
            // Upload new file to local storage
            $file = $request->file('file');
            $folder = 'SIDOPPAN/Dokumentasi';
            $filename = uniqid('', true) . '_' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs($folder, $file, $filename);
            
            Log::info('Mahasiswa standalone dokumentasi updated in local storage', ['file' => $file->getClientOriginalName()]);
            
            // Upload new file to Google Drive
            $driveFileId = null;
            $driveFileUrl = null;
            
            if ($this->googleDrive && $this->googleDrive->isConfigured()) {
                try {
                    $folderId = $this->googleDrive->getFolderIdByType('dokumentasi');
                    
                    if ($folderId) {
                        $uploadResult = $this->googleDrive->uploadFile(
                            storage_path('app/public/' . $path),
                            $file->getClientOriginalName(),
                            $folderId
                        );
                        
                        if ($uploadResult) {
                            $driveFileId = $uploadResult['file_id'];
                            $driveFileUrl = $uploadResult['file_url'];
                            Log::info('Updated file uploaded to Google Drive successfully', ['file_id' => $driveFileId]);
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to upload updated file to Google Drive', ['error' => $e->getMessage()]);
                }
            }
        }

        $dokumentasi->update([
            'judul' => $request->judul,
            'file' => $path,
            'drive_file_id' => $driveFileId,
            'drive_file_url' => $driveFileUrl,
            'uploaded_to_drive' => !empty($driveFileId),
        ]);

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Dokumentasi berhasil diperbarui!');
    }

    /**
     * Hapus dokumentasi
     */
    public function destroy($id)
    {
        $dokumentasi = Dokumentasi::findOrFail($id);

        if ($dokumentasi->file) {
            Storage::disk('public')->delete($dokumentasi->file);
        }

        $dokumentasi->delete();

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Dokumentasi berhasil dihapus!');
    }
}
