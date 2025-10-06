<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 72px;
        }

        body {
            background-color: #f7f9fb;
            font-family: 'Poppins', sans-serif;
            transition: padding-left .3s ease;
        }

        .layout-wrapper {
            min-height: 100vh;
            transition: padding-left .3s ease;
        }

        .sidebar {
            background: linear-gradient(180deg, #0061ff 0%, #60efff 100%);
            min-height: 100vh;
            padding-top: 20px;
            width: var(--sidebar-width);
            transition: width .3s ease;
            color: #fff;
            display: flex;
            flex-direction: column;
        }

        .sidebar .logo-group {
            text-align: center;
            margin-bottom: 32px;
            transition: opacity .3s ease;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 18px;
            color: inherit;
            text-decoration: none;
            font-weight: 500;
            border-left: 4px solid transparent;
            transition: background-color .2s ease, padding-left .2s ease;
        }

        .sidebar a.active {
            background-color: rgba(255, 255, 255, 0.22);
            border-left-color: #fff;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.18);
            padding-left: 24px;
        }

        .sidebar .link-label {
            transition: opacity .3s ease;
        }

        .header {
            background-color: #fff;
            border-bottom: 1px solid #ddd;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
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

        .toggle-btn {
            background-color: #e8f0fe;
            border: none;
            color: #2050a0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            transition: background-color .2s ease;
        }

        .toggle-btn:hover {
            background-color: #d2e3fc;
        }

        .content-area {
            flex-grow: 1;
        }

        body.sidebar-collapsed .sidebar {
            width: var(--sidebar-collapsed-width);
        }

        body.sidebar-collapsed .sidebar .logo-group img {
            width: 40px;
            height: 40px;
        }

        body.sidebar-collapsed .sidebar .logo-group h6,
        body.sidebar-collapsed .sidebar .link-label {
            opacity: 0;
            pointer-events: none;
            width: 0;
        }

        @media (max-width: 992px) {
            body.sidebar-collapsed .sidebar {
                transform: translateX(calc(-1 * var(--sidebar-collapsed-width)));
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                z-index: 1040;
            }

            .content-area {
                margin-left: var(--sidebar-width);
            }

            body.sidebar-collapsed .content-area {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
<div class="layout-wrapper d-flex">
    {{-- Sidebar --}}
    <div class="sidebar">
        <div class="logo-group">
            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a1/Logo_Universitas_Lambung_Mangkurat.png" alt="Logo" width="70">
            <h6 class="mt-2">Dashboard Mahasiswa</h6>
        </div>
        <a href="{{ route('mahasiswa.index') }}" class="{{ request()->routeIs('mahasiswa.index') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i>
            <span class="link-label">Beranda Penelitian</span>
        </a>
        <a href="#">
            <i class="bi bi-people-fill"></i>
            <span class="link-label">Pengabdian Dosen</span>
        </a>
    </div>

    {{-- Main Content --}}
    <div class="content-area d-flex flex-column flex-grow-1">
        <div class="header">
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="toggle-btn" id="sidebarToggle" aria-label="Sembunyikan sidebar">
                    <i class="bi bi-layout-sidebar-inset"></i>
                </button>
                <h5 class="fw-semibold mb-0">Dashboard Mahasiswa - Penelitian & Pengabdian</h5>
            </div>
            <div class="profile gap-3">
                <a href="{{ url()->previous() }}" class="toggle-btn" aria-label="Kembali">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="d-flex align-items-center gap-2">
                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile">
                    <strong>{{ Auth::user()->name ?? 'Mahasiswa' }}</strong>
                </div>
            </div>
        </div>

        <main class="p-4 flex-grow-1">
            @yield('content')
        </main>
    </div>
</div>

<script>
    document.getElementById('sidebarToggle')?.addEventListener('click', function () {
        document.body.classList.toggle('sidebar-collapsed');
    });
</script>
</body>
</html>
