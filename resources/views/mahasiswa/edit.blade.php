@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Edit Data Penelitian</h4>

    <form action="{{ route('mahasiswa.update', $mahasiswa->id) }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ $mahasiswa->nama }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $mahasiswa->email }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="Berjalan" {{ $mahasiswa->status == 'Berjalan' ? 'selected' : '' }}>Berjalan</option>
                <option value="Selesai" {{ $mahasiswa->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tahun</label>
            <input type="text" name="tahun" class="form-control" value="{{ $mahasiswa->tahun }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Peran</label>
            <select name="peran" class="form-select" required>
                <option value="Anggota" {{ $mahasiswa->peran == 'Anggota' ? 'selected' : '' }}>Anggota</option>
                <option value="Kontributor" {{ $mahasiswa->peran == 'Kontributor' ? 'selected' : '' }}>Kontributor</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection