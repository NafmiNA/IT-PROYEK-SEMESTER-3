@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">📁 Dokumentasi Saya</h4>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('mahasiswa.dokumentasi.create') }}" class="btn btn-primary mb-3">+ Tambah Dokumentasi</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Judul</th>
                <th>File</th>
                <th>Diupload Pada</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dokumentasi as $item)
                <tr>
                    <td>{{ $item->judul }}</td>
                    <td><a href="{{ asset('storage/' . $item->file_path) }}" target="_blank">Lihat File</a></td>
                    <td>{{ $item->created_at->format('d M Y') }}</td>
                    <td>
                        <form action="{{ route('mahasiswa.dokumentasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus dokumentasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted">Belum ada dokumentasi.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection