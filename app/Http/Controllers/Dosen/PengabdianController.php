<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengabdianController extends Controller
{
    public function index()
    {
        return view('dosen.pengabdian.index');
    }

    public function create()
    {
        return view('dosen.pengabdian.create');
    }

    // method store, edit, update, destroy bisa ditambahkan nanti
}
