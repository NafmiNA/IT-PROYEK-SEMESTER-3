<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use App\Models\Verifikasi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $dosen = $request->user()->dosen;

        if (!$dosen) {
            abort(403, 'Data dosen tidak ditemukan untuk pengguna ini.');
        }

        $penelitianBuilder = fn () => Penelitian::query()
            ->with(['ketua'])
            ->whereHas('dosens', function ($query) use ($dosen) {
                $query->where('dosen_id', $dosen->id);
            });

        $pengabdianBuilder = fn () => Pengabdian::query()
            ->with(['ketua'])
            ->whereHas('dosens', function ($query) use ($dosen) {
                $query->where('dosen_id', $dosen->id);
            });

        $totalPenelitian = $penelitianBuilder()->count();
        $totalPengabdian = $pengabdianBuilder()->count();

        $totalDokumentasi = Dokumentasi::query()
            ->where(function ($query) use ($dosen) {
                $query->whereHas('penelitian.dosens', function ($subQuery) use ($dosen) {
                    $subQuery->where('dosen_id', $dosen->id);
                })->orWhereHas('pengabdian.dosens', function ($subQuery) use ($dosen) {
                    $subQuery->where('dosen_id', $dosen->id);
                });
            })
            ->count();

        $menungguVerif = Verifikasi::where('dosen_id', $dosen->id)
            ->where('status', 'Menunggu')
            ->count();

        $menungguPengabdian = $pengabdianBuilder()->where('status', 'Menunggu')->count();

        $menungguVerif += $menungguPengabdian;

        $latestPenelitian = $penelitianBuilder()->latest()->take(5)->get();
        $latestPengabdian = $pengabdianBuilder()->latest()->take(5)->get();

        $currentYear = (int) now()->year;
        $startYear = $currentYear - 4;

        $penelitianPerYear = $penelitianBuilder()
            ->selectRaw('tahun, COUNT(*) as total')
            ->whereBetween('tahun', [$startYear, $currentYear])
            ->groupBy('tahun')
            ->pluck('total', 'tahun');

        $pengabdianPerYear = $pengabdianBuilder()
            ->selectRaw('tahun, COUNT(*) as total')
            ->whereBetween('tahun', [$startYear, $currentYear])
            ->groupBy('tahun')
            ->pluck('total', 'tahun');

        $trend = [];
        foreach (range($startYear, $currentYear) as $year) {
            $trend[] = [
                'tahun' => $year,
                'penelitian' => (int) ($penelitianPerYear[$year] ?? 0),
                'pengabdian' => (int) ($pengabdianPerYear[$year] ?? 0),
            ];
        }

        $yearSummary = [
            'penelitian' => (int) ($penelitianPerYear[$currentYear] ?? 0),
            'pengabdian' => (int) ($pengabdianPerYear[$currentYear] ?? 0),
            'approved' => $penelitianBuilder()->count() // Semua Penelitian dianggap disetujui
                + $pengabdianBuilder()->where('status', 'Disetujui')->count(),
            'rejected' => $pengabdianBuilder()->where('status', 'Ditolak')->count(),
        ];

        $kpi = [
            'penelitian' => $totalPenelitian,
            'pengabdian' => $totalPengabdian,
            'dokumentasi' => $totalDokumentasi,
            'pending' => $menungguVerif,
        ];

        return view('dosen.dashboard', compact(
            'dosen',
            'kpi',
            'yearSummary',
            'trend',
            'latestPenelitian',
            'latestPengabdian',
            'menungguVerif'
        ));
    }
}
