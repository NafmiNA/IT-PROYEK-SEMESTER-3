@extends('layouts.mahasiswa')

@section('content')
<style>
    /* ======== GLOBAL ======== */
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f4f6f9;
        color: #333;
    }

    .main-wrapper {
        display: flex;
        min-height: 100vh;
    }

    /* ======== SIDEBAR ======== */
    .sidebar {
        width: 250px;
        background: linear-gradient(180deg, #0061ff 0%, #60efff 100%);
        color: white;
        display: flex;
        flex-direction: column;
        padding-top: 30px;
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar .logo {
        font-size: 22px;
        font-weight: 700;
        text-align: center;
        margin-bottom: 40px;
    }

    .nav-link {
        color: #f8f9fa;
        padding: 12px 25px;
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        transition: all 0.2s ease-in-out;
        font-weight: 500;
    }

    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.15);
        padding-left: 30px;
    }

    .nav-link.active {
        background-color: rgba(255, 255, 255, 0.25);
        border-left: 4px solid #fff;
        font-weight: 600;
    }

    .nav-link i {
        font-size: 18px;
    }

    /* ======== CONTENT ======== */
    .content {
        margin-left: 250px;
        padding: 30px 40px;
        flex: 1;
    }

    .topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .topbar h4 {
        font-weight: 600;
        color: #2c3e50;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: white;
        padding: 6px 14px;
        border-radius: 30px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }

    .user-info img {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background-color: #dee2e6;
    }

    .user-info span {
        font-weight: 500;
        color: #333;
    }

    /* ======== CARD PENELITIAN ======== */
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

    .card-penelitian {
        background-color: #e9ecef;
        border-radius: 10px;
        font-weight: 600;
        color: #495057;
    }

    .card {
        border-radius: 12px;
        transition: 0.3s ease-in-out;
        border: none;
        background-color: #fff;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-outline-primary, .btn-outline-danger {
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
    }

    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: white;
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: white;
    }

    .shadow-sm {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05) !important;
    }
</style>

<div class="main-wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">📘 SIM P3M</div>
        <a href="#" class="nav-link active"><i class="bi bi-grid"></i> Dashboard Mahasiswa</a>
        <a href="#" class="nav-link"><i class="bi bi-journal-text"></i> Beranda Penelitian</a>
        <a href="#" class="nav-link"><i class="bi bi-people"></i> Pengabdian Dosen</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="topbar">
            <h4>Dashboard Mahasiswa - Penelitian & Pengabdian</h4>
            <div class="user-info">
                <img src="https://cdn-icons-png.flaticon.com/512/3177/3177440.png" alt="user">
                <span>{{ Auth::user()->name ?? 'Andi' }}</span>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success shadow-sm border-0 rounded-3">{{ session('success') }}</div>
        @endif

        <section class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                    <div>
                        <h5 class="fw-bold text-primary mb-1">📚 Daftar Penelitian Dosen</h5>
                        <p class="text-muted mb-0">Total {{ $penelitianList->count() }} penelitian aktif dan arsip.</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Judul Penelitian</th>
                                <th scope="col" class="text-nowrap">Ketua</th>
                                <th scope="col" class="text-nowrap">Status</th>
                                <th scope="col" class="text-nowrap">Tahun</th>
                                <th scope="col" class="text-end">Unggah Dokumen</th>
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
                                        <div class="fw-semibold text-body">{{ $item->judul }}</div>
                                        <div class="small text-muted">{{ $item->skema ?? '—' }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $item->ketua->nama ?? '—' }}</div>
                                        <div class="small text-muted">{{ $item->ketua->email ?? '—' }}</div>
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
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data penelitian.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                    <div>
                        <h5 class="fw-bold text-primary mb-1">🤝 Daftar Pengabdian Dosen</h5>
                        <p class="text-muted mb-0">Total {{ $pengabdianList->count() }} program pengabdian masyarakat.</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Judul Pengabdian</th>
                                <th scope="col" class="text-nowrap">Ketua</th>
                                <th scope="col" class="text-nowrap">Status</th>
                                <th scope="col" class="text-nowrap">Tahun</th>
                                <th scope="col" class="text-end">Unggah Dokumen</th>
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
                                        <div class="fw-semibold text-body">{{ $item->judul }}</div>
                                        <div class="small text-muted">{{ $item->bidang ?? '—' }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $item->ketua->nama ?? '—' }}</div>
                                        <div class="small text-muted">{{ $item->ketua->email ?? '—' }}</div>
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
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data pengabdian.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
