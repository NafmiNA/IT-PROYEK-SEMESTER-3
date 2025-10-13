@extends('layouts.mahasiswa')

@section('content')
<div class="container">
    {{-- Daftar Dokumentasi --}}
    <div class="card shadow-sm">
        <div class="card-body">
            @if ($dokumentasi->isEmpty())
                <p class="text-muted">Belum ada dokumentasi yang ditambahkan.</p>
            @else
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>File</th>
                            <th>Tanggal Upload</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dokumentasi as $item)
                            <tr>
                                <td>{{ $item->judul }}</td>
                                <td>
                                    <a href="{{ asset('storage/' . $item->file) }}" target="_blank">
                                        Lihat File
                                    </a>
                                </td>
                                <td>{{ $item->created_at->format('d M Y') }}</td>
                                <td>
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('mahasiswa.dokumentasi.edit', $item->dokumentasi_id) }}" class="btn btn-warning btn-sm">Edit</a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('mahasiswa.dokumentasi.destroy', $item->dokumentasi_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

{{-- Tampilan utama dashboard --}}
<div class="content">
    <div class="topbar">
        <h4>Dashboard Mahasiswa - Penelitian & Pengabdian</h4>
        <div class="user-info">
            <img src="https://cdn-icons-png.flaticon.com/512/3177/3177440.png" alt="user">
            <span>{{ Auth::user()->name ?? 'Mahasiswa' }}</span>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success shadow-sm border-0 rounded-3">{{ session('success') }}</div>
    @endif

    <!-- ===== PENELITIAN ===== -->
    <section class="card mb-4">
        <div class="card-body">
            <h5>📚 Daftar Penelitian Dosen</h5>
            <p class="text-muted mb-3">Total {{ $penelitianList->count() }} penelitian aktif dan arsip.</p>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Judul Penelitian</th>
                            <th>Ketua</th>
                            <th>Status</th>
                            <th>Tahun</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penelitianList as $item)
                            @php
                                $badge = [
                                    'Menunggu' => 'bg-warning text-dark',
                                    'Disetujui' => 'bg-success',
                                    'Ditolak' => 'bg-danger',
                                    'Draft' => 'bg-secondary',
                                ][$item->status] ?? 'bg-secondary';
                            @endphp
                            <tr>
                                <td><strong>{{ $item->judul }}</strong><div class="text-muted small">{{ $item->skema ?? '—' }}</div></td>
                                <td><strong>{{ $item->ketua->nama ?? '—' }}</strong><div class="text-muted small">{{ $item->ketua->email ?? '—' }}</div></td>
                                <td><span class="badge {{ $badge }}">{{ $item->status }}</span></td>
                                <td>{{ $item->tahun }}</td>
                                <td class="text-end">
                                    {{-- Upload dokumentasi baru --}}
                                    <form action="{{ route('mahasiswa.dokumentasi.store') }}" method="POST" enctype="multipart/form-data" class="d-inline-block">
                                        @csrf
                                        <input type="hidden" name="context" value="penelitian">
                                        <input type="hidden" name="context_id" value="{{ $item->id }}">
                                        <input type="file" name="dokumentasi[]" class="form-control form-control-sm mb-1" multiple required>
                                        <button type="submit" class="btn btn-success btn-sm">Unggah Dokumentasi</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data penelitian.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- ===== PENGABDIAN ===== -->
    <section class="card">
        <div class="card-body">
            <h5>🤝 Daftar Pengabdian Dosen</h5>
            <p class="text-muted mb-3">Total {{ $pengabdianList->count() }} program pengabdian masyarakat.</p>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Judul Pengabdian</th>
                            <th>Ketua</th>
                            <th>Status</th>
                            <th>Tahun</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengabdianList as $item)
                            @php
                                $badge = [
                                    'Menunggu' => 'bg-warning text-dark',
                                    'Disetujui' => 'bg-success',
                                    'Ditolak' => 'bg-danger',
                                    'Draft' => 'bg-secondary',
                                ][$item->status] ?? 'bg-secondary';
                            @endphp
                            <tr>
                                <td><strong>{{ $item->judul }}</strong><div class="text-muted small">{{ $item->bidang ?? '—' }}</div></td>
                                <td><strong>{{ $item->ketua->nama ?? '—' }}</strong><div class="text-muted small">{{ $item->ketua->email ?? '—' }}</div></td>
                                <td><span class="badge {{ $badge }}">{{ $item->status }}</span></td>
                                <td>{{ $item->tahun }}</td>
                                <td class="text-end">
                                    {{-- Upload dokumentasi baru --}}
                                    <form action="{{ route('mahasiswa.dokumentasi.store') }}" method="POST" enctype="multipart/form-data" class="d-inline-block">
                                        @csrf
                                        <input type="hidden" name="context" value="pengabdian">
                                        <input type="hidden" name="context_id" value="{{ $item->id }}">
                                        <input type="file" name="dokumentasi[]" class="form-control form-control-sm mb-1" multiple required>
                                        <button type="submit" class="btn btn-success btn-sm">Unggah Dokumentasi</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data pengabdian.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection