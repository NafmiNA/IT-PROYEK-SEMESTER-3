<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- Alpine.js for sidebar functionality --}}
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            /* Sidebar Styles - Tetap 100%, tidak zoom */
            .sidebar-main {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 256px !important;
                height: 100vh !important;
                background-color: #0f172a !important;
                color: white !important;
                z-index: 9999 !important;
                padding: 1rem !important;
                overflow-y: auto !important;
                overflow-x: hidden !important;
                transition: transform 0.3s ease !important;
            }
            
            /* Scrollbar styling untuk sidebar */
            .sidebar-main::-webkit-scrollbar {
                width: 6px;
            }
            .sidebar-main::-webkit-scrollbar-track {
                background: #1e293b;
            }
            .sidebar-main::-webkit-scrollbar-thumb {
                background: #475569;
                border-radius: 3px;
            }
            .sidebar-main::-webkit-scrollbar-thumb:hover {
                background: #64748b;
            }
            .sidebar-hidden {
                transform: translateX(-100%) !important;
            }
            
            /* Konten utama - Zoom 80% */
            .content-shifted {
                margin-left: 256px !important;
                transition: margin-left 0.3s ease !important;
                zoom: 80%;
                -moz-transform: scale(0.8);
                -moz-transform-origin: 0 0;
            }
            .content-full {
                margin-left: 0 !important;
                zoom: 80%;
                -moz-transform: scale(0.8);
                -moz-transform-origin: 0 0;
            }
            .toggle-btn-outside {
                position: fixed !important;
                top: 1rem !important;
                left: 1rem !important;
                z-index: 10 !important;
                background: #2563eb !important;
                color: white !important;
                border: none !important;
                padding: 0.5rem !important;
                border-radius: 0.5rem !important;
                cursor: pointer !important;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
            }
            .toggle-btn-outside:hover {
                background: #1d4ed8 !important;
                box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15) !important;
            }
            .toggle-btn-inside {
                position: absolute !important;
                top: 1rem !important;
                right: 1rem !important;
                z-index: 1 !important;
                background: transparent !important;
                color: white !important;
                border: none !important;
                padding: 0.5rem !important;
                border-radius: 0.5rem !important;
                cursor: pointer !important;
            }
            .toggle-btn-inside:hover {
                background: rgba(255, 255, 255, 0.1) !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-100" x-data="{ sidebarOpen: true }">
        {{-- Toggle Button (Outside - when sidebar closed) --}}
        <button @click="sidebarOpen = !sidebarOpen" 
                class="toggle-btn-outside"
                x-show="!sidebarOpen">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- SIDEBAR --}}
        <div class="sidebar-main" 
             :class="{ 'sidebar-hidden': !sidebarOpen }"
             x-show="sidebarOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full">
            
            {{-- Close Button (Inside Sidebar) --}}
            <button @click="sidebarOpen = false" 
                    class="toggle-btn-inside"
                    title="Close Sidebar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            
            <h2 style="color: white; font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem; margin-top: 3rem;">SIDOPPAN</h2>
            <p style="color: #9ca3af; font-size: 0.875rem; margin-bottom: 1.5rem;">{{ ucfirst(Auth::user()->role ?? 'Dosen') }}</p>
            
            {{-- Dashboard --}}
            @php
                $dashboardRoute = route('dosen.dashboard');
                $dashboardActive = request()->routeIs('dosen.dashboard');
                if (auth()->user()->role == 'admin') {
                    $dashboardRoute = route('admin.dashboard');
                    $dashboardActive = request()->routeIs('admin.dashboard');
                } elseif (auth()->user()->role == 'mahasiswa') {
                    $dashboardRoute = route('mahasiswa.dashboard');
                    $dashboardActive = request()->routeIs('mahasiswa.dashboard');
                }
            @endphp
            <div style="background: {{ $dashboardActive ? '#2563eb' : '#1e293b' }}; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 0.5rem;">
                <a href="{{ $dashboardRoute }}" style="color: white; text-decoration: none; display: block;">
                    Dashboard
                </a>
            </div>

            @if(auth()->user()->role !== 'mahasiswa')
                <hr style="border-color: #374151; margin: 1rem 0;">
                
                {{-- Penelitian --}}
                @php
                    $penelitianRoute = route('dosen.penelitian.index');
                    $penelitianActive = request()->routeIs('dosen.penelitian.*');
                    if (auth()->user()->role == 'admin') {
                        $penelitianRoute = route('admin.penelitian.index');
                        $penelitianActive = request()->routeIs('admin.penelitian.*');
                    }
                @endphp
                <div style="background: {{ $penelitianActive ? '#2563eb' : '#1e293b' }}; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 0.5rem;">
                    <a href="{{ $penelitianRoute }}" style="color: white; text-decoration: none; display: block;">
                        Penelitian
                    </a>
                </div>

                {{-- Pengabdian --}}
                @php
                    $pengabdianRoute = route('dosen.pengabdian.index');
                    $pengabdianActive = request()->routeIs('dosen.pengabdian.*');
                    if (auth()->user()->role == 'admin') {
                        $pengabdianRoute = route('admin.pengabdian.index');
                        $pengabdianActive = request()->routeIs('admin.pengabdian.*');
                    }
                @endphp
                <div style="background: {{ $pengabdianActive ? '#2563eb' : '#1e293b' }}; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 0.5rem;">
                    <a href="{{ $pengabdianRoute }}" style="color: white; text-decoration: none; display: block;">
                        Pengabdian
                    </a>
                </div>

                {{-- Prestasi --}}
                @php
                    $prestasiRoute = route('dosen.prestasi.index');
                    $prestasiActive = request()->routeIs('dosen.prestasi.*');
                    if (auth()->user()->role == 'admin') {
                        $prestasiRoute = route('admin.prestasi.index');
                        $prestasiActive = request()->routeIs('admin.prestasi.*') || request()->routeIs('admin.ahp.*');
                    }
                @endphp
                <div style="background: {{ $prestasiActive ? '#2563eb' : '#1e293b' }}; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 0.5rem;">
                    <a href="{{ $prestasiRoute }}" style="color: white; text-decoration: none; display: block;">
                        Prestasi
                    </a>
                </div>
            @endif

            @if(auth()->user()->role === 'mahasiswa')
                <hr style="border-color: #374151; margin: 1rem 0;">
                
                {{-- Dokumentasi --}}
                <div style="background: {{ request()->routeIs('mahasiswa.dokumentasi.*') ? '#2563eb' : '#1e293b' }}; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 0.5rem;">
                    <a href="{{ route('mahasiswa.dokumentasi.index') }}" style="color: white; text-decoration: none; display: block;">
                        Dokumentasi
                    </a>
                </div>
            @endif

            @if(auth()->user()->role == 'admin')
                <hr style="border-color: #374151; margin: 1rem 0;">
                
                {{-- Kelola Akun --}}
                <div style="background: {{ request()->routeIs('admin.users.*') ? '#2563eb' : '#1e293b' }}; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 0.5rem;">
                    <a href="{{ route('admin.users.index') }}" style="color: white; text-decoration: none; display: block;">
                        Kelola Akun
                    </a>
                </div>
            @endif

            <hr style="border-color: #374151; margin: 1rem 0;">

            {{-- Profile --}}
            <div style="background: {{ request()->routeIs('profile.*') ? '#2563eb' : '#1e293b' }}; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 0.5rem;">
                <a href="{{ route('profile.edit') }}" style="color: white; text-decoration: none; display: block;">
                    Profile
                </a>
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}" style="margin-bottom: 0;">
                @csrf
                <button type="submit" style="width: 100%; background: #1e293b; color: #ef4444; padding: 0.75rem; border-radius: 0.5rem; border: none; cursor: pointer; text-align: left; font-size: 1rem;">
                    Logout
                </button>
            </form>

            {{-- User Info --}}
            <div style="margin-top: 2rem; padding: 0.75rem; background: #1e293b; border-radius: 0.5rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 40px; height: 40px; background: linear-gradient(to bottom right, #4b5563, #374151); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <p style="color: white; font-size: 0.875rem; font-weight: 600; margin: 0;">{{ Auth::user()->name ?? 'User' }}</p>
                        <p style="color: #9ca3af; font-size: 0.75rem; margin: 0;">{{ Auth::user()->email ?? '' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content with left margin --}}
        <div class="content-shifted" 
             :class="{ 'content-full': !sidebarOpen }"
             style="min-height: 100vh;">
            {{ $slot ?? '' }}
        </div>
    </body>
</html>
