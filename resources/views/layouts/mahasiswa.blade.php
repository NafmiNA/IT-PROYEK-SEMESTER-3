<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa - P3M Sistem</title>
    
    {{-- Alpine.js CDN --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    {{-- Vite: Tailwind CSS & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
      /* Animations used in mahasiswa views */
      @keyframes slideUp { from { opacity:0; transform: translateY(12px);} to { opacity:1; transform: translateY(0);} }
      @keyframes fadeIn { from { opacity:0;} to { opacity:1;} }
      .animate-slide-up { animation: slideUp .3s ease-out both; }
      .animate-fade { animation: fadeIn .4s ease-out both; }
      
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
<body class="bg-gray-50" x-data="{ sidebarOpen: true }">
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
        
                    <h2 style="color: white; font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem; margin-top: 3rem;">SIDEPAN</h2>
            <p style="color: #9ca3af; font-size: 0.875rem; margin-bottom: 1.5rem;">Mahasiswa</p>
        
        <div style="background: {{ request()->routeIs('mahasiswa.dashboard') ? '#2563eb' : '#1e293b' }}; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 0.5rem;">
            <a href="{{ route('mahasiswa.dashboard') }}" style="color: white; text-decoration: none; display: block;">Dashboard</a>
        </div>
        <div style="background: {{ request()->routeIs('mahasiswa.dokumentasi.*') ? '#2563eb' : '#1e293b' }}; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 0.5rem;">
            <a href="{{ route('mahasiswa.dokumentasi.index') }}" style="color: white; text-decoration: none; display: block;">Dokumentasi</a>
        </div>
        
        <hr style="border-color: #374151; margin: 1rem 0;">
        
        <div style="background: {{ request()->routeIs('profile.*') ? '#2563eb' : '#1e293b' }}; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 0.5rem;">
            <a href="{{ route('profile.edit') }}" style="color: white; text-decoration: none; display: block;">Profil</a>
        </div>
        
        <form method="POST" action="{{ route('logout') }}" style="margin-bottom: 0;">
            @csrf
            <button type="submit" style="width: 100%; background: #1e293b; color: #ef4444; padding: 0.75rem; border-radius: 0.5rem; border: none; cursor: pointer; text-align: left; font-size: 1rem;">
                Logout
            </button>
        </form>
        
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
        <main class="p-6">
            @yield('content')
        </main>
    </div>
</body>
</html>
