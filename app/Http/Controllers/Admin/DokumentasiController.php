<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use App\Services\GoogleDriveService;
use Illuminate\Http\JsonResponse;
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

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'context'    => 'required|in:penelitian,pengabdian',
            'context_id' => 'required|integer',
            'files'      => 'nullable|array|min:1',
            'files.*'    => 'nullable|image|max:4096',
            'file'       => 'nullable|image|max:4096',
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
            $folder = 'SIDEPAN/' . ucfirst($data['context']) . '/' . $model->id . '/dokumentasi';
            $filename = uniqid('', true) . '_' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs($folder, $file, $filename);

            // Google Drive Upload Logic
            $driveFileId = null;
            $driveFileUrl = null;
            $uploadedToDrive = false;

            if ($this->googleDrive && $this->googleDrive->isConfigured()) {
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
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Upload to Google Drive failed: ' . $e->getMessage());
                }
            }

            $saved[] = Dokumentasi::create([
                $data['context'] === 'penelitian' ? 'penelitian_id' : 'pengabdian_id' => $model->id,
                'file_name'   => $file->getClientOriginalName(),
                'mime'        => $file->getMimeType(),
                'size'        => $file->getSize(),
                'gdrive_path' => $path,
                'drive_file_id' => $driveFileId,
                'drive_file_url' => $driveFileUrl,
                'uploaded_to_drive' => $uploadedToDrive,
            ]);
        }

        return response()->json([
            'message' => 'Dokumentasi berhasil diunggah.',
            'data'    => collect($saved)->map(fn (Dokumentasi $doc) => [
                'id'        => $doc->getKey(),
                'file_name' => $doc->file_name,
                'mime'      => $doc->mime,
                'size'      => $doc->size,
                'url'       => Storage::disk('public')->url($doc->gdrive_path),
            ]),
        ], 201);
    }

    public function destroy(Dokumentasi $dokumentasi): JsonResponse
    {
        if ($dokumentasi->gdrive_path && Storage::disk('public')->exists($dokumentasi->gdrive_path)) {
            Storage::disk('public')->delete($dokumentasi->gdrive_path);
        }

        $dokumentasi->delete();

        return response()->json([
            'message' => 'Dokumentasi dihapus.',
        ]);
    }
}
