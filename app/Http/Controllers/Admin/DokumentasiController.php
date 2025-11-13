<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumentasiController extends Controller
{
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
            $folder = $data['context'] . '/' . $model->id;
            $filename = uniqid('', true) . '_' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs($folder, $file, $filename);

            $saved[] = Dokumentasi::create([
                $data['context'] === 'penelitian' ? 'penelitian_id' : 'pengabdian_id' => $model->id,
                'file_name'   => $file->getClientOriginalName(),
                'mime'        => $file->getMimeType(),
                'size'        => $file->getSize(),
                'gdrive_path' => $path,
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
