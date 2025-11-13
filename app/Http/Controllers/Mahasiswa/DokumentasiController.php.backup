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
            if ($doc->gdrive_path && Storage::disk('public')->exists($doc->gdrive_path)) {
                Storage::disk('public')->delete($doc->gdrive_path);
            }
            $context = $doc->penelitian_id ? 'penelitian' : 'pengabdian';
            $contextId = $doc->penelitian_id ?: $doc->pengabdian_id;
            $folder = $context . '/' . $contextId;
            $file = $request->file('file');
            $filename = uniqid('', true) . '_' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs($folder, $file, $filename);

            $doc->gdrive_path = $path;
            $doc->mime = $file->getMimeType();
            $doc->size = $file->getSize();
            $doc->file_name = $data['file_name'] ?? $file->getClientOriginalName();
        } else if (!empty($data['file_name'])) {
            $doc->file_name = $data['file_name'];
        }

        $doc->save();
        return redirect()->route('mahasiswa.dokumentasi.index')->with('success', 'Dokumentasi diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $doc = Dokumentasi::findOrFail($id);
        if (!$this->isOwnedByCurrentStudent($doc)) {
            abort(403, 'Anda tidak berhak menghapus dokumen ini.');
        }

        if ($doc->gdrive_path && Storage::disk('public')->exists($doc->gdrive_path)) {
            Storage::disk('public')->delete($doc->gdrive_path);
        }
        $doc->delete();
        return redirect()->back()->with('success', 'Dokumentasi dihapus.');
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
