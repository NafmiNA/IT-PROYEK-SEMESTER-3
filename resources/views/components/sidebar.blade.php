{{-- Sidebar Component with Toggle Functionality --}}
<div x-data="{ sidebarOpen: localStorage.getItem('sidebarOpen') === 'true' }" 
     x-init="$watch('sidebarOpen', value => localStorage.setItem('sidebarOpen', value))"
     class="relative">
    
    {{-- Overlay for mobile --}}
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 lg:hidden"
         style="display: none;">
    </div>

    {{-- Sidebar --}}
    <aside x-show="sidebarOpen || window.innerWidth >= 1024"
           x-transition:enter="transform transition ease-in-out duration-300"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transform transition ease-in-out duration-300"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           class="fixed top-0 left-0 z-50 h-screen sidebar-solid text-white shadow-2xl transition-all duration-300 lg:translate-x-0 flex flex-col"
           :class="sidebarOpen ? 'w-64' : 'lg:w-20'"
           style="display: block;">
        
        {{-- Sidebar Header --}}
        <div class="flex items-center justify-between border-b border-gray-700 transition-all duration-300"
             :class="sidebarOpen ? 'p-4' : 'p-3'">
            <div class="flex items-center gap-3" x-show="sidebarOpen">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold">P3M Sistem</h2>
                    <p class="text-xs text-gray-400">{{ ucfirst(Auth::user()->role ?? 'Portal') }} Portal</p>
                </div>
            </div>
            
            {{-- Compact logo when collapsed --}}
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-lg" x-show="!sidebarOpen" x-cloak>
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
        </div>

        {{-- Navigation Menu --}}
        <nav class="flex-1 overflow-y-auto py-5 space-y-1 transition-all duration-300"
             :class="sidebarOpen ? 'px-4' : 'px-2'">
            
            {{-- 
              ==============================================================
              == MODIFIKASI: TOMBOL RUMAH (DASHBOARD) "PINTAR" ==
              ==============================================================
            --}}
            @php
                $dashboardRoute = route('dosen.dashboard'); // Default
                $dashboardActive = request()->routeIs('dosen.dashboard');
                if (auth()->user()->role == 'admin') {
                    $dashboardRoute = route('admin.dashboard');
                    $dashboardActive = request()->routeIs('admin.dashboard');
                } elseif (auth()->user()->role == 'mahasiswa') {
                    $dashboardRoute = route('mahasiswa.dashboard');
                    $dashboardActive = request()->routeIs('mahasiswa.dashboard');
                }
            @endphp
            <a href="{{ $dashboardRoute }}" 
               class="group flex w-full items-center py-2.5 rounded-lg transition-all duration-200 {{ $dashboardActive ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
               :class="sidebarOpen ? 'gap-3 px-3 justify-start' : 'gap-0 px-0 justify-center'"
               x-tooltip="'Dashboard'">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span x-show="sidebarOpen" class="font-medium">Dashboard</span>
                <span x-show="sidebarOpen && {{ $dashboardActive ? 'true' : 'false' }}" class="ml-auto">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </span>
            </a>
            {{-- =============================================================== --}}


            {{-- Divider --}}
            <div class="my-4 border-t border-gray-700"></div>
            
            <div x-show="sidebarOpen" class="px-3 mb-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu Utama</p>
            </div>

            {{-- 
              ==============================================================
              == MODIFIKASI: LINK PENELITIAN "PINTAR" ==
              ==============================================================
            --}}
            @php
                $penelitianRoute = route('dosen.penelitian.index'); // Default
                $penelitianActive = request()->routeIs('dosen.penelitian.*');
                if (auth()->user()->role == 'admin') {
                    $penelitianRoute = route('admin.penelitian.index');
                    $penelitianActive = request()->routeIs('admin.penelitian.*');
                }
            @endphp
            <a href="{{ $penelitianRoute }}" 
               class="group flex w-full items-center py-2.5 rounded-lg transition-all duration-200 {{ $penelitianActive ? 'bg-emerald-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
               :class="sidebarOpen ? 'gap-3 px-3 justify-start' : 'gap-0 px-0 justify-center'"
               x-tooltip="'Penelitian'">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span x-show="sidebarOpen" class="font-medium">Penelitian</span>
                @if($penelitianActive)
                    <span x-show="sidebarOpen" class="ml-auto">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                @endif
            </a>
            {{-- =============================================================== --}}
            
            {{-- 
              ==============================================================
              == MODIFIKASI: LINK PENGABDIAN "PINTAR" ==
              ==============================================================
            --}}
            @php
                $pengabdianRoute = route('dosen.pengabdian.index'); // Default
                $pengabdianActive = request()->routeIs('dosen.pengabdian.*');
                if (auth()->user()->role == 'admin') {
                    $pengabdianRoute = route('admin.pengabdian.index');
                    $pengabdianActive = request()->routeIs('admin.pengabdian.*');
                }
            @endphp
            <a href="{{ $pengabdianRoute }}" 
               class="group flex w-full items-center py-2.5 rounded-lg transition-all duration-200 {{ $pengabdianActive ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
               :class="sidebarOpen ? 'gap-3 px-3 justify-start' : 'gap-0 px-0 justify-center'"
               x-tooltip="'Pengabdian'">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span x-show="sidebarOpen" class="font-medium">Pengabdian</span>
                @if($pengabdianActive)
                    <span x-show="sidebarOpen" class="ml-auto">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                @endif
            </a>
            {{-- =============================================================== --}}

            {{-- 
              ==============================================================
              == MODIFIKASI: LINK PRESTASI "PINTAR" ==
              ==============================================================
            --}}
            @php
                $prestasiRoute = route('dosen.prestasi.index'); // Default
                $prestasiActive = request()->routeIs('dosen.prestasi.*');
                if (auth()->user()->role == 'admin') {
                    // Ganti '#' dengan rute admin prestasi jika sudah Anda buat
                    $prestasiRoute = '#'; 
                    $prestasiActive = request()->routeIs('admin.prestasi.*');
                }
            @endphp
            <a href="{{ $prestasiRoute }}" 
               class="group flex w-full items-center py-2.5 rounded-lg transition-all duration-200 {{ $prestasiActive ? 'bg-yellow-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
               :class="sidebarOpen ? 'gap-3 px-3 justify-start' : 'gap-0 px-0 justify-center'"
               x-tooltip="'Kelola Prestasi'">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
                <span x-show="sidebarOpen" class="font-medium">Kelola Prestasi</span>
                @if($prestasiActive)
                    <span x-show="sidebarOpen" class="ml-auto">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                @endif
            </a>
            {{-- =============================================================== --}}


            {{-- 
              ==============================================================
              == TAMBAHAN: MENU KHUSUS ADMIN ==
              ==============================================================
            --}}
            @if(auth()->user()->role == 'admin')
                {{-- Divider Admin --}}
                <div class="my-4 border-t border-gray-700"></div>
                <div x-show="sidebarOpen" class="px-3 mb-2">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Admin</p>
                </div>

                {{-- Kelola Akun --}}
                <a href="{{ route('admin.users.index') }}" 
                   class="group flex w-full items-center py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-rose-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
                   :class="sidebarOpen ? 'gap-3 px-3 justify-start' : 'gap-0 px-0 justify-center'"
                   x-tooltip="'Kelola Akun'">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="font-medium">Kelola Akun</span>
                     @if(request()->routeIs('admin.users.*'))
                        <span x-show="sidebarOpen" class="ml-auto">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    @endif
                </a>
            @endif
            {{-- =============================================================== --}}


            {{-- Divider --}}
            <div class="my-4 border-t border-gray-700"></div>
            
            <div x-show="sidebarOpen" class="px-3 mb-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Akun</p>
            </div>

            {{-- Profile --}}
            <a href="{{ route('profile.edit') }}" 
               class="group flex w-full items-center py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('profile.*') ? 'bg-amber-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
               :class="sidebarOpen ? 'gap-3 px-3 justify-start' : 'gap-0 px-0 justify-center'"
               x-tooltip="'Profile'">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span x-show="sidebarOpen" class="font-medium">Profile</span>
                @if(request()->routeIs('profile.*'))
                    <span x-show="sidebarOpen" class="ml-auto">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                @endif
            </a>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="w-full group flex items-center py-2.5 rounded-lg transition-all duration-200 text-gray-300 hover:bg-red-600/20 hover:text-red-400"
                        :class="sidebarOpen ? 'gap-3 px-3 justify-start' : 'gap-0 px-0 justify-center'"
                        x-tooltip="'Logout'">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span x-show="sidebarOpen" class="font-medium">Logout</span>
                </button>
            </form>
        </nav>

        {{-- User Info (bottom) --}}
        <div class="border-t border-gray-700 transition-all duration-300"
             :class="sidebarOpen ? 'p-4' : 'p-3'">
            <div class="flex items-center gap-3" x-show="sidebarOpen">
                <div class="w-10 h-10 bg-gradient-to-br from-gray-600 to-gray-700 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email ?? '' }}</p>
                </div>
            </div>
            
            {{-- Compact user icon when collapsed --}}
            <div class="w-10 h-10 bg-gradient-to-br from-gray-600 to-gray-700 rounded-full flex items-center justify-center text-white font-bold shadow-lg mx-auto" x-show="!sidebarOpen" x-cloak>
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
            </div>
        </div>

        {{-- Toggle Button (Desktop) --}}
        <button @click="sidebarOpen = !sidebarOpen"
                class="hidden lg:flex absolute -right-3 top-20 w-6 h-6 bg-blue-600 hover:bg-blue-700 text-white rounded-full items-center justify-center shadow-lg transition-all duration-200 z-50">
            <svg class="w-3 h-3 transition-transform duration-300" :class="{'rotate-180': !sidebarOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
    </aside>

    {{-- Toggle Button (Mobile - Floating) --}}
    <button @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden fixed bottom-6 right-6 w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center shadow-2xl z-50 transition-all duration-200 hover:scale-110">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!sidebarOpen">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="sidebarOpen" style="display: none;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    {{-- Main Content Wrapper --}}
    <div class="transition-all duration-300"
         :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-20'">
        {{ $slot }}
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .sidebar-solid {
        background-color: #0f172a; /* Tailwind slate-900 for full opacity */
        backdrop-filter: none;
    }
</style>