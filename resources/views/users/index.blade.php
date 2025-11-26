<x-app-layout>
    {{-- REFAKTOR: Menambahkan style konsisten dari halaman lain --}}
    <style>
        @keyframes slideUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        
        .animate-slide-up { animation: slideUp 0.3s ease-out both; }
        .animate-fade { animation: fadeIn 0.4s ease-out both; }
        
        .card-hover { 
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover { 
            transform: translateY(-4px); 
            box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.15), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
        }
        
        .focus-visible:focus-visible { 
            outline: 3px solid #3b82f6; 
            outline-offset: 2px; 
        }
        
        .bg-subtle {
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(59, 130, 246, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 90% 80%, rgba(147, 51, 234, 0.04) 0%, transparent 50%);
        }
        
        .stat-num {
            transition: transform 0.3s ease;
        }
        .card-hover:hover .stat-num {
            transform: scale(1.08);
        }

        .action-group { display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap; justify-content:flex-end; }
        .action-btn {
            display:inline-flex; align-items:center; justify-content:center; gap:0.375rem;
            height:36px; padding:0 12px; border-radius:10px; font-weight:600; font-size:0.875rem; white-space:nowrap;
            box-shadow: 0 1px 1px rgba(0,0,0,0.04);
            transition-colors: 0.2s ease;
        }
        .action-btn svg { width:16px; height:16px; }

        .btn-detail { background-color:#3b82f6; color:#fff; } /* blue-600 */
        .btn-detail:hover { background-color:#2563eb; } /* blue-700 */
        
        .btn-edit { background-color:#f97316; color:#fff; } /* orange-500 */
        .btn-edit:hover { background-color:#ea580c; } /* orange-600 */

        .btn-delete { background-color:#dc2626; color:#fff; } /* red-600 */
        .btn-delete:hover { background-color:#b91c1c; } /* red-700 */
    </style>

    {{-- REFAKTOR: Mengganti div utama agar konsisten --}}
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 bg-subtle">
    
        {{-- REFAKTOR: Mengganti header agar konsisten --}}
        <header class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    
                    {{-- Kiri: Breadcrumb & Judul --}}
                    <div class="animate-fade">
                        {{-- Breadcrumb --}}
                        <nav class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="flex items-center gap-1 hover:text-blue-600 transition-colors focus-visible">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Dashboard
                            </a>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span class="font-medium text-gray-900">Manajemen User</span>
                        </nav>
    
                        {{-- Judul & Tombol Kembali --}}
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="group flex-shrink-0 inline-flex items-center justify-center w-11 h-11 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-md hover:shadow-lg transition-all duration-200 focus-visible"
                               aria-label="Kembali ke Dashboard">
                                {{-- Ikon panah yang sudah benar (tipis) --}}
                                <svg class="w-5 h-5 transform group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                            </a>
                            <div>
                                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                                    Manajemen User
                                </h1>
                                <p class="text-sm text-gray-600 mt-1">
                                    Pantau dan kelola semua akun pengguna
                                </p>
                            </div>
                        </div>
                    </div>
    
                    {{-- Kanan: Search & Add Button --}}
                    <div class="mt-4 flex items-center gap-3 flex-wrap">
                        <div class="relative">
                            {{-- REFAKTOR: Menambahkan id dan onkeyup --}}
                            <input type="text" 
                                   id="searchInput"
                                   onkeyup="searchUsers(this.value)"
                                   class="w-64 sm:w-80 pl-9 pr-4 py-2.5 text-sm bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all focus-visible" 
                                   placeholder="Cari user (Nama, Email)...">
                            <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        
                        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-colors focus-visible">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Tambah User Baru
                        </a>
                    </div>
    
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

            {{-- PHP Block untuk Menghitung Role --}}
            @php
                $totalUsers = $users->count();
                $roleCounts = [
                    'total'     => $totalUsers,
                    'admin'     => $users->where('role', 'admin')->count(),
                    'dosen'     => $users->where('role', 'dosen')->count(),
                    'mahasiswa' => $users->where('role', 'mahasiswa')->count(),
                ];
            @endphp

            {{-- Stat Cards - Semua Putih --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-6">
                
                {{-- Total Card --}}
                <div class="text-left w-full bg-white rounded-lg shadow-md border border-gray-200 card-hover p-5 animate-slide-up" style="animation-delay: 0.05s">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-11 h-11 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 016-6h6m6 0a6 6 0 00-4-5.658M15 21v-1.111a6 6 0 01-1.079-3.289"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl sm:text-4xl font-bold text-gray-900 stat-num mb-1">{{ $roleCounts['total'] }}</p>
                    <p class="text-sm text-gray-600 font-medium">Total User</p>
                </div>
            
                {{-- Admin Card --}}
                <div class="text-left w-full bg-white rounded-lg shadow-md border border-gray-200 card-hover p-5 animate-slide-up" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-11 h-11 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl sm:text-4xl font-bold text-gray-900 stat-num mb-1">{{ $roleCounts['admin'] }}</p>
                    <p class="text-sm text-gray-600 font-medium">Total Admin</p>
                </div>
            
                {{-- Dosen Card --}}
                <div class="text-left w-full bg-white rounded-lg shadow-md border border-gray-200 card-hover p-5 animate-slide-up" style="animation-delay: 0.15s">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-11 h-11 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 21h14a2 2 0 002-2v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl sm:text-4xl font-bold text-gray-900 stat-num mb-1">{{ $roleCounts['dosen'] }}</p>
                    <p class="text-sm text-gray-600 font-medium">Total Dosen</p>
                </div>
            
                {{-- Mahasiswa Card --}}
                <div class="text-left w-full bg-white rounded-lg shadow-md border border-gray-200 card-hover p-5 animate-slide-up" style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-11 h-11 bg-gray-100 rounded-lg flex items-center justify-center">
                           <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl sm:text-4xl font-bold text-gray-900 stat-num mb-1">{{ $roleCounts['mahasiswa'] }}</p>
                    <p class="text-sm text-gray-600 font-medium">Total Mahasiswa</p>
                </div>
            </div>


            {{-- REFAKTOR: Mengganti wrapper tabel agar konsisten --}}
            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                
                {{-- REFAKTOR: Menambahkan header tabel + tombol filter --}}
                <div class="px-5 sm:px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Daftar User</h2>
                            <p class="text-sm text-gray-600 mt-0.5">{{ $totalUsers }} user terdaftar</p>
                        </div>
                        
                        {{-- Filter Buttons --}}
                        <div class="flex flex-wrap gap-2">
                            <button onclick="filterUsers('all')" 
                                    class="filter-btn px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors focus-visible">
                                Semua
                            </button>
                            <button onclick="filterUsers('admin')" 
                                    class="filter-btn px-4 py-2 text-sm font-medium bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors focus-visible">
                                Admin
                            </button>
                            <button onclick="filterUsers('dosen')" 
                                    class="filter-btn px-4 py-2 text-sm font-medium bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors focus-visible">
                                Dosen
                            </button>
                            <button onclick="filterUsers('mahasiswa')" 
                                    class="filter-btn px-4 py-2 text-sm font-medium bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors focus-visible">
                                Mahasiswa
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Notifikasi Sukses (dipindahkan ke atas) --}}
                @if (session('success'))
                    <div class="p-5 sm:p-6 border-b border-gray-200">
                        <div class="flex items-start gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-lg shadow-sm">
                            <div class="flex-shrink-0 w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-emerald-900">Berhasil!</p>
                                <p class="text-sm text-emerald-700">{{ session('success') }}</p>
                            </div>
                            <button onclick="this.closest('.p-5').remove()" 
                                    class="flex-shrink-0 text-emerald-500 hover:text-emerald-700 transition-colors focus-visible"
                                    aria-label="Tutup">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                {{-- Tabel --}}
                <div class="overflow-x-auto">
                    {{-- REFAKTOR: Mengganti style tabel --}}
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="userList">
                            @forelse ($users as $user)
                                {{-- REFAKTOR: Mengganti class tr --}}
                                <tr class="user-row transition hover:bg-gray-50" data-role="{{ $user->role }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{-- Badge Role (Style ini sudah bagus & konsisten) --}}
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
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        {{-- REFAKTOR: Mengganti style tombol aksi --}}
                                        <div class="action-group">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="action-btn btn-edit">
                                                {{-- Icon (Opsional, tapi konsisten) --}}
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Edit
                                            </a>
                                            
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus user ini?')" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn btn-delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
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
        </main>
    </div>


    {{-- REFAKTOR: JavaScript diganti total agar konsisten --}}
    <script>
        // Filter by role
        function filterUsers(role) {
            const rows = document.querySelectorAll('.user-row');
            const buttons = document.querySelectorAll('.filter-btn');
            const searchInput = document.getElementById('searchInput');
            
            if (searchInput) searchInput.value = '';
            
            buttons.forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-700');
            });
            
            const targetButton = Array.from(buttons).find(btn => btn.getAttribute('onclick') === `filterUsers('${role}')`);
            
            if (targetButton) {
                targetButton.classList.add('bg-blue-600', 'text-white');
                targetButton.classList.remove('bg-gray-100', 'text-gray-700');
            } else if (role === 'all') {
                buttons[0].classList.add('bg-blue-600', 'text-white');
                buttons[0].classList.remove('bg-gray-100', 'text-gray-700');
            }
            
            rows.forEach(row => {
                const rowRole = row.dataset.role;
                const isMatch = (role === 'all' || rowRole === role);
                row.style.display = isMatch ? 'table-row' : 'none';
            });
        }
        
        // Search functionality
        function searchUsers(query) {
            const rows = document.querySelectorAll('.user-row');
            const buttons = document.querySelectorAll('.filter-btn');
            const searchTerm = query.toLowerCase().trim();
            
            // Reset filters
            buttons.forEach((btn, i) => {
                btn.classList.remove('bg-blue-600', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-700');
                if (i === 0) {
                    btn.classList.remove('bg-gray-100', 'text-gray-700');
                    btn.classList.add('bg-blue-600', 'text-white');
                }
            });

            // Filter rows based on search
            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchTerm)) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Set filter 'all' on page load
        document.addEventListener('DOMContentLoaded', () => {
            filterUsers('all');
        });
    </script>
</x-app-layout>