<x-app-layout>
    
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">

            {{-- Breadcrumb --}}
            <nav class="text-sm mb-3" aria-label="Breadcrumb">
                <ol class="list-none p-0 inline-flex items-center space-x-2">
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a>
                    </li>
                    <li class="flex items-center">
                        <svg class="fill-current w-3 h-3 mx-2 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                        </svg>
                        <span class="text-gray-700 font-medium">Manajemen User</span>
                    </li>
                </ol>
            </nav>

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                
                {{-- Left Section: Arrow, Title, Live Badge, Description --}}
                <div class="flex-grow">
                    <div class="flex items-center gap-2">
                        
                        {{-- =================================================== --}}
                        {{-- PERBAIKAN: Mengganti SVG panah agar lebih tipis --}}
                        {{-- =================================================== --}}
                        {{-- Arrow Back (Versi Kotak Biru) --}}
                        <a href="{{ route('admin.dashboard') }}" class="bg-blue-600 text-white rounded-md p-2.5 hidden sm:block hover:bg-blue-700 transition">
                            {{-- Ikon panah outline (lebih tipis) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                            </svg>
                        </a>
                        
                        {{-- Title --}}
                        <h1 class="text-3xl font-bold text-gray-900">
                            Manajemen User
                        </h1>
                        {{-- Live Badge (opsional, jika memang relevan untuk Manajemen User) --}}
                        {{-- <span class="ml-2 px-2 py-0.5 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Live</span> --}}
                    </div>
                    {{-- Description --}}
                    <p class="text-gray-600 mt-1 sm:ml-10"> {{-- Tambah ml-10 di sm --}}
                        Pantau dan kelola semua akun pengguna
                    </p>
                </div>

                {{-- Right Section: Search Bar and Add Button --}}
                <div class="flex items-center gap-3 w-full md:w-auto mt-4 md:mt-0">
                    <div class="relative flex-grow">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" 
                               class="block w-full bg-white border border-gray-300 rounded-md py-2 pl-10 pr-3 text-sm placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition" 
                               placeholder="Cari user...">
                    </div>
                    
                    <a href="{{ route('admin.users.create') }}" class="flex-shrink-0 inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Tambah User Baru
                    </a>
                </div>

            </div>
        </div>
    </header>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- =================================================================== --}}
            {{-- BLOK PHP (TETAP SAMA) --}}
            {{-- =================================================================== --}}
            @php
                $totalUsers = $users->count();
                $roleCounts = [
                    'total'     => $totalUsers,
                    'admin'     => $users->where('role', 'admin')->count(),
                    'dosen'     => $users->where('role', 'dosen')->count(),
                    'mahasiswa' => $users->where('role', 'mahasiswa')->count(),
                ];
            @endphp

            {{-- =================================================================== --}}
            {{-- STAT CARD HEADER (TETAP SAMA) --}}
            {{-- =================================================================== --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-6">
                
                {{-- Total Card (Biru) --}}
                <button onclick="filterRole('all')" 
                        class="filter-role-btn text-left w-full bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md p-5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-blue-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-11 h-11 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 016-6h6m6 0a6 6 0 00-4-5.658M15 21v-1.111a6 6 0 01-1.079-3.289"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl sm:text-4xl font-bold text-white mb-1">{{ $roleCounts['total'] }}</p>
                    <p class="text-sm text-blue-100 font-medium">Total User</p>
                </button>
            
                {{-- Admin Card (Rose) --}}
                <button onclick="filterRole('admin')" 
                        class="filter-role-btn text-left w-full bg-rose-600 hover:bg-rose-700 rounded-lg shadow-md p-5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-rose-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-11 h-11 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl sm:text-4xl font-bold text-white mb-1">{{ $roleCounts['admin'] }}</p>
                    <p class="text-sm text-rose-100 font-medium">Total Admin</p>
                </button>
            
                {{-- Dosen Card (Sky) --}}
                <button onclick="filterRole('dosen')" 
                        class="filter-role-btn text-left w-full bg-sky-600 hover:bg-sky-700 rounded-lg shadow-md p-5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-sky-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-11 h-11 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 21h14a2 2 0 002-2v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl sm:text-4xl font-bold text-white mb-1">{{ $roleCounts['dosen'] }}</p>
                    <p class="text-sm text-sky-100 font-medium">Total Dosen</p>
                </button>
            
                {{-- Mahasiswa Card (Emerald) --}}
                <button onclick="filterRole('mahasiswa')" 
                        class="filter-role-btn text-left w-full bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-md p-5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-emerald-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-11 h-11 bg-white/20 rounded-lg flex items-center justify-center">
                           <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl sm:text-4xl font-bold text-white mb-1">{{ $roleCounts['mahasiswa'] }}</p>
                    <p class="text-sm text-emerald-100 font-medium">Total Mahasiswa</p>
                </button>
            </div>


            {{-- =================================================================== --}}
            {{-- KONTEN UTAMA (TABEL) --}}
            {{-- =================================================================== --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Menampilkan notifikasi sukses --}}
                    @if (session('success'))
                        <div class="mb-4 p-3 rounded bg-emerald-50 text-emerald-700 border border-emerald-200">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Tabel --}}
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full border-collapse border border-gray-200">
                            <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                <tr>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Nama</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Email</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Role</th>
                                    <th class="border border-gray-200 px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white text-sm text-gray-700">
                                @forelse ($users as $user)
                                    <tr class="user-row transition hover:bg-gray-50" data-role="{{ $user->role }}">
                                        <td class="border border-gray-200 px-4 py-2 font-medium text-gray-900">{{ $user->name }}</td>
                                        <td class="border border-gray-200 px-4 py-2">{{ $user->email }}</td>
                                        <td class="border border-gray-200 px-4 py-2">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                @if($user->role == 'admin') bg-rose-100 text-rose-700
                                                @elseif($user->role == 'dosen') bg-blue-100 text-blue-700
                                                @elseif($user->role == 'mahasiswa') bg-emerald-100 text-emerald-700
                                                @else bg-gray-100 text-gray-700
                                                @endif
                                                ">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="border border-gray-200 px-4 py-2">
                                            <div class="flex justify-end gap-2 text-xs font-semibold">
                                                {{-- Tombol Edit --}}
                                                <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center gap-2 rounded-full border border-amber-300 px-3 py-1 text-amber-600 transition hover:bg-amber-500 hover:text-white">
                                                    Edit
                                                </a>
                                                
                                                {{-- Tombol Hapus --}}
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus user ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-rose-300 px-3 py-1 text-rose-600 transition hover:bg-rose-500 hover:text-white">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">
                                            Belum ada data user.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>


    {{-- =================================================================== --}}
    {{-- JAVASCRIPT (TETAP SAMA) --}}
    {{-- =================================================================== --}}
    <script>
        function filterRole(role) {
            const rows = document.querySelectorAll('.user-row'); 
            
            rows.forEach(row => {
                const rowRole = row.dataset.role; 
                const isMatch = (role === 'all' || rowRole === role);
        
                if (isMatch) {
                    row.style.display = 'table-row'; 
                } else {
                    row.style.display = 'none';
                }
            });
    
            const buttons = document.querySelectorAll('.filter-role-btn');
            buttons.forEach(btn => {
                btn.classList.remove('ring-4', 'ring-white/50');
                if (btn.onclick.toString().includes(`'${role}'`)) {
                    btn.classList.add('ring-4', 'ring-white/50'); 
                }
            });
        }
    
        document.addEventListener('DOMContentLoaded', () => {
            filterRole('all');
        });
    </script>
</x-app-layout>