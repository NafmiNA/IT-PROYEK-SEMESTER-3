@extends('layouts.mahasiswa')

@section('content')
<div class="container">
    <h4 class="mb-3">Edit dokumentasi Penelitian</h4>

    <form action="{{ route('mahasiswa.update', $mahasiswa->id) }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        @method('PUT')

          <div class="mb-3">
            <label for="judul" class="form-label">Judul Penelitian</label>
            <input type="text" name="judul" class="form-control" value="{{ $mahasiswa->judul }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $mahasiswa->email }}" required>
        </div>

        <div class="mb-3">
            <label for="dokumen" class="form-label">Upload Dokumen Baru</label>
            <input type="file" name="dokumen" class="form-control" accept=".pdf,.doc,.docx">
            @if($mahasiswa->dokumen)
                <p class="mt-2">Dokumen saat ini:
                    <a href="{{ asset('storage/'.$mahasiswa->dokumen) }}" target="_blank">Lihat</a>
                </p>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection