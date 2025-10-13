@extends('layouts.mahasiswa')

@section('content')
<div class="container">
    <h4 class="mb-3">Edit Data Penelitian</h4>

    <form action="{{ route('mahasiswa.update', $mhs->id) }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ $mhs->nama }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $mhs->email }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="Berjalan" {{ $mhs->status == 'Berjalan' ? 'selected' : '' }}>Berjalan</option>
                <option value="Selesai" {{ $mhs->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tahun</label>
            <input type="text" name="tahun" class="form-control" value="{{ $mhs->tahun }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Peran</label>
            <select name="peran" class="form-select" required>
                <option value="Anggota" {{ $mhs->peran == 'Anggota' ? 'selected' : '' }}>Anggota</option>
                <option value="Kontributor" {{ $mhs->peran == 'Kontributor' ? 'selected' : '' }}>Kontributor</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Upload Ulang Dokumen (Opsional)</label>
            <input type="file" name="file" class="form-control">
            @if($mhs->file)
                <small>File saat ini: <a href="{{ asset('storage/' . $mhs->file) }}" target="_blank">Lihat</a></small>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection