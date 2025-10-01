<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pengabdian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengabdianController extends Controller
{
    public function index(Request $request)
    {
        $dosenId = optional($request->user()->dosen)->id;

        // amankan kalau relasi dosen belum ada
        if (!$dosenId) {
            abort(403, 'Akun belum terhubung ke data dosen.');
        }

        $pengabdian = Pengabdian::where('dosen_id', $dosenId)
            ->latest()
            ->paginate(10);

        // ⬇️ kirim dengan nama yang SAMA seperti di blade
        return view('dosen.pengabdian.index', compact('pengabdian'));
    }


    public function create()
    {
        return view('dosen.pengabdian.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'       => 'required|string|max:255',
            'tahun'       => 'required|integer|min:2000|max:'.(date('Y')+1),
            'skema'       => 'nullable|string|max:100',
            'sumber_dana' => 'nullable|string|max:100',
            'dana'        => 'nullable',
            'status'      => 'required|in:Menunggu,Disetujui,Ditolak',
        ]);

        // normalisasi dana (boleh berformat 15.000.000)
        $data['dana'] = $data['dana'] ? preg_replace('/\D/', '', $data['dana']) : null;

        $data['dosen_id'] = $request->user()->dosen->id ?? null;

        Pengabdian::create($data);

        return redirect()->route('dosen.pengabdian.index')
            ->with('success', 'Pengabdian berhasil ditambahkan.');
    }

    public function show(Pengabdian $pengabdian)
    {
        $this->authorizePengabdian($pengabdian);
        return view('dosen.pengabdian.show', compact('pengabdian'));
    }

    public function edit(Pengabdian $pengabdian)
    {
        $this->authorizePengabdian($pengabdian);
        return view('dosen.pengabdian.edit', compact('pengabdian'));
    }

    public function update(Request $request, Pengabdian $pengabdian)
    {
        $this->authorizePengabdian($pengabdian);

        $data = $request->validate([
            'judul'       => 'required|string|max:255',
            'tahun'       => 'required|integer|min:2000|max:'.(date('Y')+1),
            'skema'       => 'nullable|string|max:100',
            'sumber_dana' => 'nullable|string|max:100',
            'dana'        => 'nullable',
            'status'      => 'required|in:Menunggu,Disetujui,Ditolak',
        ]);
        $data['dana'] = $data['dana'] ? preg_replace('/\D/', '', $data['dana']) : null;

        $pengabdian->update($data);

        return redirect()->route('dosen.pengabdian.index')
            ->with('success', 'Pengabdian diperbarui.');
    }

    public function destroy(Pengabdian $pengabdian)
    {
        $this->authorizePengabdian($pengabdian);
        $pengabdian->delete();

        return back()->with('success', 'Pengabdian dihapus.');
    }

    private function authorizePengabdian(Pengabdian $p)
    {
        $uid = Auth::user()->dosen->id ?? null;
        abort_unless($uid && $p->dosen_id === $uid, 403);
    }
}
