<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f9fb;
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            background-color: #f1f3f4;
            min-height: 100vh;
            padding-top: 20px;
            width: 250px;
        }
        .sidebar a {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            font-weight: 500;
        }
        .sidebar a.active {
            background-color: #d6e4f0;
            border-left: 4px solid #0d6efd;
        }
        .sidebar a:hover {
            background-color: #e0e7ee;
        }
        .header {
            background-color: #fff;
            border-bottom: 1px solid #ddd;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header img {
            height: 40px;
        }
        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .profile img {
            border-radius: 50%;
            height: 40px;
        }
        .card-penelitian {
            background-color: #9aa8a1;
            color: #000;
            border-radius: 15px;
            padding: 10px 20px;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
    </style>
</head>
<body>
<div class="d-flex">
    {{-- Sidebar --}}
    <div class="sidebar">
        <div class="text-center mb-4">
            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a1/Logo_Universitas_Lambung_Mangkurat.png" alt="Logo" width="70">
            <h6 class="mt-2">Dashboard Mahasiswa</h6>
        </div>
        <a href="{{ route('mahasiswa.index') }}" class="{{ request()->routeIs('mahasiswa.index') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i> Beranda Penelitian
        </a>
        <a href="#"><i class="bi bi-people-fill"></i> Pengabdian Dosen</a>
    </div>

    {{-- Main Content --}}
    <div class="flex-grow-1">
        <div class="header">
            <div>
                <h5 class="fw-semibold">Dashboard Mahasiswa - Penelitian & Pengabdian</h5>
            </div>
            <div class="profile">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile">
                <strong>{{ Auth::user()->name ?? 'Mahasiswa' }}</strong>
            </div>
        </div>

        <main class="p-4">
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
