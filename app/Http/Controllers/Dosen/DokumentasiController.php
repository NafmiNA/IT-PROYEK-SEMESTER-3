<?php

namespace App\Http\Controllers\Dosen;

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use Illuminate\Http\Request;

class DokumentasiController extends Controller
{
    public function index()
    {
        $dokumentasi = Dokumentasi::all();
        return view('dosen.dokumentasi.index', compact('dokumentasi'));
    }
}
