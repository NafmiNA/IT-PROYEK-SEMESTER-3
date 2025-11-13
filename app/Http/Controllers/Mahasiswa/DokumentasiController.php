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
                // Delete old file from Google Drive if exists
                if ($doc->google_id) {
                    $this->googleDrive->delete($doc->google_id);
                    Log::info('Old mahasiswa dokumentasi deleted from Google Drive', ['google_id' => $doc->google_id]);
                }

                $context = $doc->penelitian_id ? 'penelitian' : 'pengabdian';
                $contextId = $doc->penelitian_id ?: $doc->pengabdian_id;
                $folder = 'Mahasiswa/' . ucfirst($context) . '/' . $contextId;
                
                $file = $request->file('file');
                
                // Upload new file to Google Drive
                $uploadResult = $this->googleDrive->upload($file, $folder);
                
                Log::info('Mahasiswa dokumentasi updated', [
                    'doc_id' => $id,
                    'new_file' => $uploadResult['filename'],
                    'google_id' => $uploadResult['google_id'] ?? null,
                ]);

                $doc->gdrive_path = $uploadResult['path'];
                $doc->google_id = $uploadResult['google_id'] ?? null;
                $doc->google_url = $uploadResult['url'] ?? null;
                $doc->mime = $uploadResult['mime_type'];
                $doc->size = $uploadResult['size'];
                $doc->file_name = $data['file_name'] ?? $uploadResult['original_name'];
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
        return redirect()->route('mahasiswa.dokumentasi.index')->with('success', 'Dokumentasi diperbarui di Google Drive.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $doc = Dokumentasi::findOrFail($id);
        if (!$this->isOwnedByCurrentStudent($doc)) {
            abort(403, 'Anda tidak berhak menghapus dokumen ini.');
        }

        try {
            // Delete from Google Drive if exists
            if ($doc->google_id) {
                $this->googleDrive->delete($doc->google_id);
                Log::info('Mahasiswa dokumentasi deleted from Google Drive', [
                    'doc_id' => $id,
                    'google_id' => $doc->google_id,
                ]);
            }
            
            $doc->delete();
            return redirect()->back()->with('success', 'Dokumentasi dihapus dari Google Drive.');
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
                // Build folder path: Mahasiswa/Penelitian/ID or Mahasiswa/Pengabdian/ID
                $folder = 'Mahasiswa/' . ucfirst($data['context']) . '/' . $model->id;
                
                // Upload to Google Drive
                $uploadResult = $this->googleDrive->upload($file, $folder);
                
                Log::info('Mahasiswa dokumentasi uploaded', [
                    'context' => $data['context'],
                    'context_id' => $model->id,
                    'file' => $uploadResult['filename'],
                    'google_id' => $uploadResult['google_id'] ?? null,
                ]);

                Dokumentasi::create([
                    $data['context'] === 'penelitian' ? 'penelitian_id' : 'pengabdian_id' => $model->id,
                    'file_name'   => $uploadResult['original_name'],
                    'mime'        => $uploadResult['mime_type'],
                    'size'        => $uploadResult['size'],
                    'gdrive_path' => $uploadResult['path'],
                    'google_id'   => $uploadResult['google_id'] ?? null,
                    'google_url'  => $uploadResult['url'] ?? null,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to upload mahasiswa dokumentasi', [
                    'error' => $e->getMessage(),
                    'file' => $file->getClientOriginalName(),
                ]);
                return back()->withErrors(['dokumentasi' => 'Gagal mengunggah file: ' . $file->getClientOriginalName()]);
            }
        }

        return back()->with('success', 'Dokumentasi berhasil diunggah ke Google Drive.');
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
