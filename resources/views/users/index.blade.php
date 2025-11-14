<x-app-layout>
    {{-- Ini akan mengisi bagian 'header' di layout Anda --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- =================================================================== --}}
            {{-- KODE BARU: BLOK @PHP UNTUK MENGHITUNG USER --}}
            {{-- =================================================================== --}}
            @php
                // 1. Menggunakan variabel $users yang dikirim dari Controller
                // 2. Menghitung berdasarkan 'role' (diasumsikan 'admin', 'dosen', 'mahasiswa')
                //    (Ini didasarkan pada kode badge Anda)
                $totalUsers = $users->count();
                $roleCounts = [
                    'total'     => $totalUsers,
                    'admin'     => $users->where('role', 'admin')->count(),
                    'dosen'     => $users->where('role', 'dosen')->count(),
                    'mahasiswa' => $users->where('role', 'mahasiswa')->count(),
                ];
            @endphp

            {{-- =================================================================== --}}
            {{-- KODE BARU: STAT CARD HEADER --}}
            {{-- =================================================================== --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-6">
                
                {{-- Total Card (Biru - Primary) --}}
                <button onclick="filterRole('all')" 
                        class="filter-role-btn text-left w-full bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md p-5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-blue-300"
                        style="animation-delay: 0.05s">
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
            
                {{-- Admin Card (Rose - Sesuai Badge) --}}
                <button onclick="filterRole('admin')" 
                        class="filter-role-btn text-left w-full bg-rose-600 hover:bg-rose-700 rounded-lg shadow-md p-5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-rose-300"
                        style="animation-delay: 0.1s">
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
            
                {{-- Dosen Card (Sky - Sesuai Badge) --}}
                <button onclick="filterRole('dosen')" 
                        class="filter-role-btn text-left w-full bg-sky-600 hover:bg-sky-700 rounded-lg shadow-md p-5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-sky-300"
                        style="animation-delay: 0.15s">
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
            
                {{-- Mahasiswa Card (Emerald - Sesuai Badge) --}}
                <button onclick="filterRole('mahasiswa')" 
                        class="filter-role-btn text-left w-full bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-md p-5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-emerald-300"
                        style="animation-delay: 0.2s">
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
            {{-- KONTEN ASLI ANDA (DIMULAI DARI SINI) --}}
            {{-- =================================================================== --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Menampilkan notifikasi sukses (jika ada) --}}
                    @if (session('success'))
                        <div class="mb-4 p-3 rounded bg-emerald-50 text-emerald-700 border border-emerald-200">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="flex items-center gap-4 mb-4">
                        
                        {{-- Tombol Kembali --}}
                        <a href="{{ route('admin.dashboard') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-full px-5 py-2 text-sm font-semibold
                                  bg-white text-gray-700 hover:bg-gray-100 border border-gray-300
                                  transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>

                        {{-- Tombol untuk 'tambah akun' --}}
                        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 rounded-full bg-[#2050A0] px-5 py-2 text-sm font-semibold text-white shadow-md transition hover:bg-[#163B78]">
                            <span class="grid h-6 w-6 place-content-center rounded-full bg-white/15 text-lg">+</span>
                            Tambah User Baru
                        </a>
                    </div>


                    {{-- Tabel untuk 'lihat akun' --}}
                    <table class="table-auto w-full border-collapse border border-gray-400">
                        <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500">
                            <tr>
                                <th class="border border-gray-300 px-4 py-3 text-left">Nama</th>
                                <th class="border border-gray-300 px-4 py-3 text-left">Email</th>
                                <th class="border border-gray-300 px-4 py-3 text-left">Role</th>
                                <th class="border border-gray-300 px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white text-sm text-gray-700">
                            {{-- Loop data $users (dari UserController@index) --}}
                            @forelse ($users as $user)
                            
                                {{-- =================================================================== --}}
                                {{-- PERUBAHAN: Menambahkan class="user-row" dan data-role --}}
                                {{-- =================================================================== --}}
                                <tr class="user-row transition hover:bg-gray-50" data-role="{{ $user->role }}">
                                    <td class="border border-gray-300 px-4 py-2 font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $user->email }}</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            @if($user->role == 'admin') bg-rose-100 text-rose-700
                                            @elseif($user->role == 'dosen') bg-blue-100 text-blue-700
                                            @elseif($user->role == 'mahasiswa') bg-emerald-100 text-emerald-700
                                            @else bg-gray-100 text-gray-700
                                            @endif">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <div class="flex justify-end gap-2 text-xs font-semibold">
                                            {{-- Tombol untuk 'edit akun' --}}
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center gap-2 rounded-full border border-amber-300 px-3 py-1 text-amber-600 transition hover:bg-amber-500 hover:text-white">
                                                Edit
                                            </a>
                                            
                                            {{-- Tombol untuk 'hapus akun' --}}
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


    {{-- =================================================================== --}}
    {{-- KODE BARU: JAVASCRIPT UNTUK FILTER --}}
    {{-- =================================================================== --}}
    <script>
        // Filter untuk tabel berdasarkan role
        function filterRole(role) {
            const rows = document.querySelectorAll('.user-row'); // Mengambil semua baris dengan class 'user-row'
            
            rows.forEach(row => {
                // Mengambil data-role dari setiap baris
                const rowRole = row.dataset.role; 
                
                // Cek apakah role-nya 'all' atau sama dengan role baris
                const isMatch = (role === 'all' || rowRole === role);
        
                if (isMatch) {
                    // Tampilkan baris (gunakan 'table-row' untuk tabel)
                    row.style.display = 'table-row'; 
                } else {
                    // Sembunyikan baris
                    row.style.display = 'none';
                }
            });
    
            // (Opsional) Mengubah style tombol stat card yang aktif
            const buttons = document.querySelectorAll('.filter-role-btn');
            buttons.forEach(btn => {
                // Reset semua style tombol
                btn.classList.remove('ring-4', 'ring-white/50');
                if (btn.onclick.toString().includes(`'${role}'`)) {
                    // Tambahkan style 'ring' ke tombol yang diklik
                    btn.classList.add('ring-4', 'ring-white/50'); 
                }
            });
        }
    
        // Panggil filter 'all' saat halaman dimuat 
        // agar style tombol 'Total User' aktif
        document.addEventListener('DOMContentLoaded', () => {
            filterRole('all');
        });
    </script>
</x-app-layout>