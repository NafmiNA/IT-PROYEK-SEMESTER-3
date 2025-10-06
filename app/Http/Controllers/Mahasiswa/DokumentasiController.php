<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumentasiController extends Controller
{
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

        foreach ($request->file('dokumentasi', []) as $file) {
            $folder = $data['context'] . '/' . $model->id;
            $filename = uniqid('', true) . '_' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs($folder, $file, $filename);

            Dokumentasi::create([
                $data['context'] === 'penelitian' ? 'penelitian_id' : 'pengabdian_id' => $model->id,
                'file_name'   => $file->getClientOriginalName(),
                'mime'        => $file->getMimeType(),
                'size'        => $file->getSize(),
                'gdrive_path' => $path,
            ]);
        }

        return back()->with('success', 'Dokumentasi berhasil diunggah.');
    }
}
