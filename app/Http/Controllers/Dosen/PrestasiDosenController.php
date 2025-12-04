<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\PrestasiDosen;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrestasiDosenController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();
        
        if (!$dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan');
        }

        // Get prestasi per tahun
        $prestasi = PrestasiDosen::where('dosen_id', $dosen->id)
            ->orderBy('tahun', 'desc')
            ->get();

        // Calculate current year stats
        $currentYear = date('Y');
        $currentPrestasi = $this->calculatePrestasi($dosen->id, $currentYear);

        return view('dosen.prestasi.index', compact('prestasi', 'currentPrestasi', 'currentYear', 'dosen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer|min:2000|max:2100',
            'publikasi' => 'required|integer|min:0',
            'hibah' => 'required|integer|min:0',
            'skor_sinta' => 'required|integer|min:0',
            'buku' => 'required|integer|min:0',
        ]);

        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan');
        }

        // Create or update prestasi
        PrestasiDosen::updateOrCreate(
            [
                'dosen_id' => $dosen->id,
                'tahun' => $request->tahun,
            ],
            [
                'publikasi' => $request->publikasi,
                'hibah' => $request->hibah,
                'skor_sinta' => $request->skor_sinta,
                'buku' => $request->buku,
            ]
        );

        return redirect()->route('dosen.prestasi.index')
            ->with('success', 'Prestasi berhasil disimpan!');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        $prestasi = PrestasiDosen::where('id', $id)
            ->where('dosen_id', $dosen->id)
            ->firstOrFail();

        // Recalculate publikasi & hibah for current year
        $calculated = $this->calculatePrestasi($dosen->id, $prestasi->tahun);

        return view('dosen.prestasi.edit', compact('prestasi', 'calculated'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'publikasi' => 'required|integer|min:0',
            'hibah' => 'required|integer|min:0',
            'skor_sinta' => 'required|integer|min:0',
            'buku' => 'required|integer|min:0',
        ]);

        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        $prestasi = PrestasiDosen::where('id', $id)
            ->where('dosen_id', $dosen->id)
            ->firstOrFail();

        $prestasi->update([
            'publikasi' => $request->publikasi,
            'hibah' => $request->hibah,
            'skor_sinta' => $request->skor_sinta,
            'buku' => $request->buku,
        ]);

        return redirect()->route('dosen.prestasi.index')
            ->with('success', 'Prestasi berhasil diupdate!');
    }

    /**
     * Calculate publikasi & hibah from penelitian & pengabdian
     */
    private function calculatePrestasi($dosenId, $tahun)
    {
        // PUBLIKASI = Penelitian (SEMUA, tidak ada verifikasi) + Pengabdian (yang DISETUJUI)
        $jumlahPenelitian = Penelitian::where('dosen_id', $dosenId)
            ->where('tahun', $tahun)
            ->count();
        
        $jumlahPengabdianDisetujui = Pengabdian::where('dosen_id', $dosenId)
            ->where('tahun', $tahun)
            ->where('status', 'Disetujui')
            ->count();
        
        $totalPublikasi = $jumlahPenelitian + $jumlahPengabdianDisetujui;

        // HIBAH = Dana Penelitian (SEMUA) + Dana Pengabdian (yang DISETUJUI)
        $hibahPenelitian = Penelitian::where('dosen_id', $dosenId)
            ->where('tahun', $tahun)
            ->sum('dana');

        $hibahPengabdian = Pengabdian::where('dosen_id', $dosenId)
            ->where('tahun', $tahun)
            ->where('status', 'Disetujui')
            ->sum('dana');

        $totalHibah = $hibahPenelitian + $hibahPengabdian;

        return [
            'publikasi' => $totalPublikasi,
            'hibah' => $totalHibah,
        ];
    }
}
