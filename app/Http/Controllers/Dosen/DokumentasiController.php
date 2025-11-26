<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use App\Services\GoogleDriveService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumentasiController extends Controller
{
    protected $googleDrive;

    public function __construct(GoogleDriveService $googleDrive)
    {
        $this->googleDrive = $googleDrive;
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'context'    => 'required|in:penelitian,pengabdian',
            'context_id' => 'required|integer',
            'files'      => 'nullable|array|min:1',
            'files.*'    => 'nullable|file|max:10240', // 10MB max
            'file'       => 'nullable|file|max:10240',
        ]);

        $files = [];
        if ($request->hasFile('files')) {
            $files = $request->file('files');
        } elseif ($request->hasFile('file')) {
            $files = [$request->file('file')];
        }

        if (empty($files)) {
            return response()->json([
                'message' => 'Tidak ada berkas yang diunggah.',
            ], 422);
        }

        $model = $data['context'] === 'penelitian'
            ? Penelitian::findOrFail($data['context_id'])
            : Pengabdian::findOrFail($data['context_id']);

        $saved = [];
        foreach ($files as $file) {
            try {
                // Store file to local storage
                $folder = 'SIDOPPAN/' . ucfirst($data['context']) . '/' . $model->id . '/dokumentasi';
                $filename = uniqid('', true) . '_' . $file->getClientOriginalName();
                $path = Storage::disk('public')->putFileAs($folder, $file, $filename);

                $saved[] = Dokumentasi::create([
                    $data['context'] === 'penelitian' ? 'penelitian_id' : 'pengabdian_id' => $model->id,
                    'file_name'   => $file->getClientOriginalName(),
                    'mime'        => $file->getMimeType(),
                    'size'        => $file->getSize(),
                    'gdrive_path' => $path,
                ]);
            } catch (\Exception $e) {
                \Log::error('Upload file failed: ' . $e->getMessage());
            }
        }

        return response()->json([
            'message' => 'Dokumentasi berhasil diunggah.',
            'data'    => collect($saved)->map(fn (Dokumentasi $doc) => [
                'id'        => $doc->getKey(),
                'file_name' => $doc->file_name,
                'mime'      => $doc->mime,
                'size'      => $doc->size,
                'url'       => $this->getFileUrl($doc->gdrive_path),
            ]),
        ], 201);
    }

    public function destroy(Dokumentasi $dokumentasi): JsonResponse
    {
        // Try to delete from Google Drive first
        try {
            $this->googleDrive->delete($dokumentasi->gdrive_path);
        } catch (\Exception $e) {
            \Log::error('Delete from Google Drive failed: ' . $e->getMessage());
            
            // Fallback: try to delete from local storage
            if ($dokumentasi->gdrive_path && Storage::disk('public')->exists($dokumentasi->gdrive_path)) {
                Storage::disk('public')->delete($dokumentasi->gdrive_path);
            }
        }

        $dokumentasi->delete();

        return response()->json([
            'message' => 'Dokumentasi dihapus.',
        ]);
    }

    /**
     * Get file URL (Google Drive or local storage)
     */
    protected function getFileUrl(string $path): string
    {
        try {
            $url = $this->googleDrive->getUrl($path);
            if ($url) {
                return $url;
            }
        } catch (\Exception $e) {
            \Log::error('Get Google Drive URL failed: ' . $e->getMessage());
        }
        
        // Fallback to local storage URL
        return Storage::disk('public')->url($path);
    }
}
