@extends('layouts.mahasiswa')

@section('content')
<div class="container">
    <h4 class="mb-3">Tambah Data Penelitian</h4>

    <form action="{{ route('mahasiswa.store') }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
    @csrf
    ...
    <div class="mb-3">
        <label class="form-label">Upload Dokumentasi (Opsional)</label>
        <input type="file" name="file" class="form-control">
    </div>
    ...
</form>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="Berjalan">Berjalan</option>
                <option value="Selesai">Selesai</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tahun</label>
            <input type="text" name="tahun" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Peran</label>
            <select name="peran" class="form-select" required>
                <option value="Anggota">Anggota</option>
                <option value="Kontributor">Kontributor</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
