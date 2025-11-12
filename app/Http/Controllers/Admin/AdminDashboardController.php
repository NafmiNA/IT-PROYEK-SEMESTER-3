<?php

// MODIFIKASI: Pastikan namespace/folder Anda benar
namespace App\Http\Controllers\Admin; // <-- INI YANG PENTING

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use App\Models\Verifikasi;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller 
{
    public function index(Request $request)
    {
        // MODIFIKASI: Kita tidak mengambil 'dosen', tapi 'user' Admin yang sedang login
        // Variabel ini akan dipakai untuk menampilkan nama "Selamat datang, Admin"
        $dosen = $request->user(); 

        // MODIFIKASI: Query builder ADMIN (Global, tanpa filter per dosen)
        $penelitianBuilder = fn () => Penelitian::query()->with(['ketua']);
        $pengabdianBuilder = fn () => Pengabdian::query()->with(['ketua']);

        // MODIFIKASI: Ambil TOTAL data (Global)
        $totalPenelitian = $penelitianBuilder()->count();
        $totalPengabdian = $pengabdianBuilder()->count();
        $totalDokumentasi = Dokumentasi::query()->count(); // Global

        // MODIFIKASI: Ambil data verifikasi (Global)
        $menungguVerif = Verifikasi::where('status', 'Menunggu')->count();
        $menungguPengabdian = $pengabdianBuilder()->where('status', 'Menunggu')->count();
        $menungguVerif += $menungguPengabdian;

        // Ambil data terbaru (Global)
        $latestPenelitian = $penelitianBuilder()->latest()->take(5)->get();
        $latestPengabdian = $pengabdianBuilder()->latest()->take(5)->get();

        // Ambil data Tren Chart (Global)
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

        // Ambil data Ringkasan Tahun (Global)
        $yearSummary = [
            'penelitian' => (int) ($penelitianPerYear[$currentYear] ?? 0),
            'pengabdian' => (int) ($pengabdianPerYear[$currentYear] ?? 0),
            'approved' => $penelitianBuilder()->where('tahun', $currentYear)->where('status', 'Disetujui')->count()
                + $pengabdianBuilder()->where('tahun', $currentYear)->where('status', 'Disetujui')->count(),
            'rejected' => $penelitianBuilder()->where('tahun', $currentYear)->where('status', 'Ditolak')->count()
                + $pengabdianBuilder()->where('tahun', $currentYear)->where('status', 'Ditolak')->count(),
        ];

        // Kumpulkan data KPI (Global)
        $kpi = [
            'penelitian' => $totalPenelitian,
            'pengabdian' => $totalPengabdian,
            'dokumentasi' => $totalDokumentasi,
            'pending' => $menungguVerif,
        ];

        // MODIFIKASI: Mengirim data ke view 'admin.dashboard'
        return view('admin.dashboard', compact(
            'dosen', // Tetap 'dosen' agar view kloningan tidak error
            'kpi',
            'yearSummary',
            'trend',
            'latestPenelitian',
            'latestPengabdian',
            'menungguVerif'
        ));
    }
}