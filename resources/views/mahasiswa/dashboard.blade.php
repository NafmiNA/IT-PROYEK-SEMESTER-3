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

        <p>Selamat datang di dashboard mahasiswa. Kamu tergabung dalam kegiatan <strong>Penelitian dan Pengabdian Dosen</strong>.</p>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-primary">📚 Daftar Penelitian Dosen</h5>
            <a href="{{ route('mahasiswa.create') }}" class="btn btn-success shadow-sm">+ Tambah Dokumen</a>
        </div>

        <div class="card-penelitian d-flex justify-content-between px-3 py-2">
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
                    <small class="text-muted">{{ $mhs->email }}</small>
                </div>
                <div class="text-center">{{ $mhs->status }}</div>
                <div class="text-center">{{ $mhs->tahun }}</div>
                <div class="text-center">{{ $mhs->peran }}</div>
                <div class="d-flex gap-2 justify-content-end">
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
</div>
@endsection
