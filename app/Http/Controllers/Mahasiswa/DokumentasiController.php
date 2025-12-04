<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use App\Services\GoogleDriveService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DokumentasiController extends Controller
{
    protected $googleDrive;

    public function __construct(GoogleDriveService $googleDrive)
    {
        $this->googleDrive = $googleDrive;
    }
    public function create()
    {
        return redirect()->route('mahasiswa.dokumentasi.index')
            ->with('success', 'Unggah dokumentasi dari kartu Penelitian/Pengabdian di dashboard.');
    }
    public function index()
    {
        $mhs = \App\Models\Mahasiswa::firstWhere('email', auth()->user()?->email);
        if (!$mhs) {
            return redirect()->route('mahasiswa.dashboard')->withErrors(['dokumentasi' => 'Akun Anda bukan mahasiswa.']);
        }

        $penelitianIds = $mhs->penelitians()->pluck('penelitian.id');
        $pengabdianIds = $mhs->pengabdians()->pluck('pengabdians.id');

        $items = Dokumentasi::with(['penelitian', 'pengabdian'])
            ->whereIn('penelitian_id', $penelitianIds)
            ->orWhereIn('pengabdian_id', $pengabdianIds)
            ->latest('dokumentasi_id')
            ->paginate(12);

        return view('mahasiswa.dokumentasi.index', compact('items'));
    }

    public function show(int $id)
    {
        $doc = Dokumentasi::with(['penelitian', 'pengabdian'])->findOrFail($id);
        if (!$this->isOwnedByCurrentStudent($doc)) {
            abort(403, 'Anda tidak berhak melihat dokumen ini.');
        }
        return view('mahasiswa.dokumentasi.show', compact('doc'));
    }

    public function edit(int $id)
    {
        $doc = Dokumentasi::with(['penelitian', 'pengabdian'])->findOrFail($id);
        if (!$this->isOwnedByCurrentStudent($doc)) {
            abort(403, 'Anda tidak berhak mengubah dokumen ini.');
        }
        return view('mahasiswa.dokumentasi.edit', compact('doc'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $doc = Dokumentasi::findOrFail($id);
        if (!$this->isOwnedByCurrentStudent($doc)) {
            abort(403, 'Anda tidak berhak mengubah dokumen ini.');
        }

        $data = $request->validate([
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'file_name' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('file')) {
            try {
                // Delete old file from local storage if exists
                if ($doc->gdrive_path && Storage::disk('public')->exists($doc->gdrive_path)) {
                    Storage::disk('public')->delete($doc->gdrive_path);
                    Log::info('Old mahasiswa dokumentasi deleted from local storage', ['path' => $doc->gdrive_path]);
                }

                $context = $doc->penelitian_id ? 'penelitian' : 'pengabdian';
                $contextId = $doc->penelitian_id ?: $doc->pengabdian_id;
                $folder = 'SIDOPPAN/' . ucfirst($context) . '/' . $contextId . '/dokumentasi';
                
                $file = $request->file('file');
                
                // Store file to local storage
                $filename = uniqid('', true) . '_' . $file->getClientOriginalName();
                $path = Storage::disk('public')->putFileAs($folder, $file, $filename);
                
                Log::info('Mahasiswa dokumentasi updated', [
                    'doc_id' => $id,
                    'new_file' => $file->getClientOriginalName(),
                    'path' => $path,
                ]);

                $doc->gdrive_path = $path;
                $doc->mime = $file->getMimeType();
                $doc->size = $file->getSize();
                $doc->file_name = $data['file_name'] ?? $file->getClientOriginalName();
            } catch (\Exception $e) {
                Log::error('Failed to update mahasiswa dokumentasi', [
                    'error' => $e->getMessage(),
                    'doc_id' => $id,
                ]);
                return back()->withErrors(['file' => 'Gagal memperbarui file.']);
            }
        } else if (!empty($data['file_name'])) {
            $doc->file_name = $data['file_name'];
        }

        $doc->save();
        return redirect()->route('mahasiswa.dokumentasi.index')->with('success', 'Dokumentasi berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $doc = Dokumentasi::findOrFail($id);
        if (!$this->isOwnedByCurrentStudent($doc)) {
            abort(403, 'Anda tidak berhak menghapus dokumen ini.');
        }

        try {
            // Delete from local storage if exists
            if ($doc->gdrive_path && Storage::disk('public')->exists($doc->gdrive_path)) {
                Storage::disk('public')->delete($doc->gdrive_path);
                Log::info('Mahasiswa dokumentasi deleted from local storage', [
                    'doc_id' => $id,
                    'path' => $doc->gdrive_path,
                ]);
            }
            
            $doc->delete();
            return redirect()->back()->with('success', 'Dokumentasi berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Failed to delete mahasiswa dokumentasi', [
                'error' => $e->getMessage(),
                'doc_id' => $id,
            ]);
            return redirect()->back()->withErrors(['dokumentasi' => 'Gagal menghapus dokumentasi.']);
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'context'    => 'required|in:penelitian,pengabdian',
            'context_id' => 'required|integer',
            'dokumentasi'   => 'required|array|min:1',
            'dokumentasi.*' => 'file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        $model = $data['context'] === 'penelitian'
            ? Penelitian::findOrFail($data['context_id'])
            : Pengabdian::findOrFail($data['context_id']);

        // Gate: hanya mahasiswa yang terdaftar di tim boleh upload
        $userEmail = auth()->user()?->email;
        $mhs = \App\Models\Mahasiswa::firstWhere('email', $userEmail);
        if (!$mhs) {
            return back()->withErrors(['dokumentasi' => 'Akun Anda tidak terdaftar sebagai mahasiswa.']);
        }

        $isAllowed = $data['context'] === 'penelitian'
            ? $mhs->penelitians()->where('penelitian.id', $model->id)->exists()
            : $mhs->pengabdians()->where('pengabdians.id', $model->id)->exists();

        if (!$isAllowed) {
            return back()->withErrors(['dokumentasi' => 'Anda tidak termasuk di tim, unggah ditolak.']);
        }

        foreach ($request->file('dokumentasi', []) as $file) {
            try {
                // Store file to local storage
                $folder = 'SIDOPPAN/' . ucfirst($data['context']) . '/' . $model->id . '/dokumentasi';
                $filename = uniqid('', true) . '_' . $file->getClientOriginalName();
                $path = Storage::disk('public')->putFileAs($folder, $file, $filename);
                
                Log::info('Mahasiswa dokumentasi uploaded to local storage', [
                    'context' => $data['context'],
                    'context_id' => $model->id,
                    'file' => $file->getClientOriginalName(),
                    'path' => $path,
                ]);

                // Try to upload to Google Drive
                $driveFileId = null;
                $driveFileUrl = null;
                $uploadedToDrive = false;
                
                if ($this->googleDrive) {
                    Log::info('GoogleDrive service available, checking configuration...');
                    
                    if ($this->googleDrive->isConfigured()) {
                        try {
                            $folderId = $this->googleDrive->getFolderIdByType(strtolower($data['context']));
                            
                            if ($folderId) {
                                $uploadResult = $this->googleDrive->uploadFile(
                                    storage_path('app/public/' . $path),
                                    $file->getClientOriginalName(),
                                    $folderId
                                );
                                
                                if ($uploadResult) {
                                    $driveFileId = $uploadResult['file_id'];
                                    $driveFileUrl = $uploadResult['file_url'];
                                    $uploadedToDrive = true;
                                    
                                    Log::info('File uploaded to Google Drive successfully', [
                                        'file_id' => $driveFileId,
                                        'file_url' => $driveFileUrl,
                                    ]);
                                }
                            } else {
                                Log::warning('Google Drive folder ID not found for type: ' . $data['context']);
                            }
                        } catch (\Exception $e) {
                            Log::error('Failed to upload to Google Drive', [
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString(),
                            ]);
                        }
                    } else {
                        Log::warning('Google Drive not configured properly');
                    }
                } else {
                    Log::warning('GoogleDrive service not available');
                }

                Dokumentasi::create([
                    $data['context'] === 'penelitian' ? 'penelitian_id' : 'pengabdian_id' => $model->id,
                    'file_name'   => $file->getClientOriginalName(),
                    'mime'        => $file->getMimeType(),
                    'size'        => $file->getSize(),
                    'gdrive_path' => $path,
                    'drive_file_id' => $driveFileId,
                    'drive_file_url' => $driveFileUrl,
                    'uploaded_to_drive' => $uploadedToDrive,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to upload mahasiswa dokumentasi', [
                    'error' => $e->getMessage(),
                    'file' => $file->getClientOriginalName(),
                ]);
                return back()->withErrors(['dokumentasi' => 'Gagal mengunggah file: ' . $file->getClientOriginalName()]);
            }
        }

        return back()->with('success', 'Dokumentasi berhasil diunggah.');
    }

    private function isOwnedByCurrentStudent(Dokumentasi $doc): bool
    {
        $mhs = \App\Models\Mahasiswa::firstWhere('email', auth()->user()?->email);
        if (!$mhs) return false;
        if ($doc->penelitian_id) {
            return $mhs->penelitians()->where('penelitian.id', $doc->penelitian_id)->exists();
        }
        if ($doc->pengabdian_id) {
            return $mhs->pengabdians()->where('pengabdians.id', $doc->pengabdian_id)->exists();
        }
        return false;
    }
}
