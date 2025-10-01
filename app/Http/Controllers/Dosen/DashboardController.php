<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use App\Models\Dokumentasi;
use App\Models\Verifikasi;
use Illuminate\Http\Request;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{

// Removed duplicate index() method to resolve duplicate symbol declaration error.

    public function index(Request $request)
    {
        $dosen = $request->user()->dosen; // relasi user->dosen

        $totalPenelitian   = Penelitian::where('dosen_id', $dosen->id)->count();
        $totalPengabdian   = Pengabdian::where('dosen_id', $dosen->id)->count();
        $totalDokumentasi = Dokumentasi::whereHas('penelitian', function ($q) use ($dosen) {
            $q->where('dosen_id', $dosen->id);
        })->orWhereHas('pengabdian', function ($q) use ($dosen) {
            $q->where('dosen_id', $dosen->id);
        })->count();
        

        $menungguVerif = Verifikasi::where('dosen_id', $dosen->id)
            ->where('status','Menunggu')->count();

        $recentPenelitian = Penelitian::where('dosen_id',$dosen->id)
            ->latest()->take(5)->get();

        $recentPengabdian = Pengabdian::where('dosen_id',$dosen->id)
            ->latest()->take(5)->get();

        $recentVerif = Verifikasi::where('dosen_id',$dosen->id)
            ->latest()->take(5)->get();

        return view('dosen.dashboard', compact(
            'dosen','totalPenelitian','totalPengabdian','totalDokumentasi',
            'menungguVerif','recentPenelitian','recentPengabdian','recentVerif'
        ));
    }

    
}
