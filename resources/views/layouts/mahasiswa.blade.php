<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    {{-- Tambahkan Tailwind/Vite agar view dashboard yang memakai Tailwind ter-render rapi --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
      /* Animations used in mahasiswa views */
      @keyframes slideUp { from { opacity:0; transform: translateY(12px);} to { opacity:1; transform: translateY(0);} }
      @keyframes fadeIn { from { opacity:0;} to { opacity:1;} }
      .animate-slide-up { animation: slideUp .3s ease-out both; }
      .animate-fade { animation: fadeIn .4s ease-out both; }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="bg-primary text-white p-3" style="width: 250px; min-height: 100vh;">
            <h5 class="mb-4">📘 Dashboard Mahasiswa</h5>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="{{ route('mahasiswa.dashboard') }}" class="nav-link text-white">🏠 Beranda Penelitian</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('mahasiswa.dokumentasi.index') }}" class="nav-link text-white">📎 Dokumentasi</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('dosen.pengabdian.index') }}" class="nav-link text-white">🤝 Pengabdian Dosen</a>
                </li>
            </ul>
        </div>

        <!-- Konten Utama -->
        <div class="flex-grow-1 p-4 bg-light">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold">Dashboard Mahasiswa - Penelitian & Pengabdian</h4>
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-secondary text-white p-2">
                        👤
                    </div>
                    <span>{{ Auth::user()->name ?? 'Mahasiswa' }}</span>
                </div>
            </div>

            {{-- Konten Halaman --}}
            <main>
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
