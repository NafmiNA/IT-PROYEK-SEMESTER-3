@extends('layouts.mahasiswa')

@section('content')
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f5f7fa;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .content {
        padding: 40px 50px;
        flex: 1;
    }

    .topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .topbar h4 {
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: white;
        padding: 8px 16px;
        border-radius: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: 0.3s;
    }

    .user-info:hover {
        transform: scale(1.02);
    }

    .user-info img {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background-color: #dee2e6;
    }

    .user-info span {
        font-weight: 500;
        color: #333;
    }

    /* CARD */
    .card {
        border-radius: 14px;
        border: none;
        background-color: #fff;
        transition: 0.3s ease-in-out;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 18px rgba(0, 0, 0, 0.12);
    }

    .card-body {
        padding: 25px 30px;
    }

    .card h5 {
        display: flex;
        align-items: center;
        font-weight: 600;
        color: #0072ff;
        margin-bottom: 6px;
    }

    .text-muted {
        color: #6c757d !important;
    }

    /* TABLE */
    .table {
        margin-bottom: 0;
        border-radius: 12px;
        overflow: hidden;
    }

    .table thead {
        background-color: #f1f3f5;
    }

    .table th {
        font-weight: 600;
        color: #333;
        border: none;
    }

    .table td {
        vertical-align: middle;
        border-top: 1px solid #eee;
    }

    .badge {
        font-size: 0.85rem;
        padding: 6px 10px;
        border-radius: 8px;
    }

    /* BUTTON */
    .btn-success {
        background-color: #28a745;
        border: none;
        border-radius: 8px;
        padding: 8px 14px;
        font-weight: 500;
        transition: 0.3s;
    }

    .btn-success:hover {
        background-color: #218838;
        transform: scale(1.05);
    }

    .btn-outline-primary,
    .btn-outline-danger {
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
    }

    .btn-outline-primary:hover {
        background-color: #007bff;
        color: white;
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: white;
    }

</style>

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
                            <th class="text-end">Unggah Dokumen</th>
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
                                $canUpload = in_array($item->id, ($penelitianAllowed ?? []));
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $item->judul }}</strong>
                                    <div class="text-muted small">{{ $item->skema ?? '—' }}</div>
                                </td>
                                <td>
                                    <strong>{{ $item->ketua->nama ?? '—' }}</strong>
                                    <div class="text-muted small">{{ $item->ketua->email ?? '—' }}</div>
                                </td>
                                <td><span class="badge {{ $badge }}">{{ $item->status }}</span></td>
                                <td>{{ $item->tahun }}</td>
                                <td class="text-end">
                                    @if($canUpload)
                                        <form action="{{ route('mahasiswa.dokumentasi.store') }}" method="POST" enctype="multipart/form-data" class="d-inline-flex align-items-center gap-2">
                                            @csrf
                                            <input type="hidden" name="context" value="penelitian">
                                            <input type="hidden" name="context_id" value="{{ $item->id }}">
                                            <input type="file" name="dokumentasi[]" class="form-control form-control-sm" multiple required>
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Unggah</button>
                                        </form>
                                    @else
                                        <span class="badge bg-secondary">Bukan anggota tim</span>
                                    @endif
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
                            <th class="text-end">Unggah Dokumen</th>
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
                                $canUpload = in_array($item->id, ($pengabdianAllowed ?? []));
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $item->judul }}</strong>
                                    <div class="text-muted small">{{ $item->bidang ?? '—' }}</div>
                                </td>
                                <td>
                                    <strong>{{ $item->ketua->nama ?? '—' }}</strong>
                                    <div class="text-muted small">{{ $item->ketua->email ?? '—' }}</div>
                                </td>
                                <td><span class="badge {{ $badge }}">{{ $item->status }}</span></td>
                                <td>{{ $item->tahun }}</td>
                                <td class="text-end">
                                    @if($canUpload)
                                        <form action="{{ route('mahasiswa.dokumentasi.store') }}" method="POST" enctype="multipart/form-data" class="d-inline-flex align-items-center gap-2">
                                            @csrf
                                            <input type="hidden" name="context" value="pengabdian">
                                            <input type="hidden" name="context_id" value="{{ $item->id }}">
                                            <input type="file" name="dokumentasi[]" class="form-control form-control-sm" multiple required>
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Unggah</button>
                                        </form>
                                    @else
                                        <span class="badge bg-secondary">Bukan anggota tim</span>
                                    @endif
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
