@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Halo, {{ Auth::user()->name ?? 'Nurlaila' }}</h4>
    <p>Kamu Tergabung dalam Penelitian dan Pengabdian Dosen.</p>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold text-primary">Penelitian Dosen</h5>
        <a href="{{ route('mahasiswa.create') }}" class="btn btn-success">+ Tambah Dokumen</a>
    </div>

    <div class="card-penelitian d-flex justify-content-between px-3 py-2 fw-bold">
        <div class="w-50">Judul Penelitian</div>
        <div class="w-25 text-center">Status</div>
        <div class="w-10 text-center">Tahun</div>
        <div class="w-25 text-center">Peran</div>
    </div>

    @foreach ($mahasiswa as $mhs)
    <div class="card mt-3 shadow-sm p-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <strong>{{ $mhs->nama }}</strong><br>
                <small>{{ $mhs->email }}</small>
            </div>
            <div class="text-center">{{ $mhs->status }}</div>
            <div class="text-center">{{ $mhs->tahun }}</div>
            <div class="text-center">{{ $mhs->peran }}</div>
           <div class="d-flex gap-2">
    <a href="{{ route('mahasiswa.edit', $mhs->id) }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-pencil"></i>
    </a>
    <form action="{{ route('mahasiswa.destroy', $mhs->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
        @csrf
        @method('DELETE')
        <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
    </form>
</div>

        </div>
    </div>
    @endforeach
</div>
@endsection