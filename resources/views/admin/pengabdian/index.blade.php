<x-app-layout>

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
    </style>

    {{-- REFAKTOR: Latar belakang diubah agar konsisten --}}
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 bg-subtle">
        
        {{-- =================================================================== --}}
        {{-- HEADER --}}
        {{-- =================================================================== --}}
        <header class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                {{-- REFAKTOR: Menggunakan layout flexbox agar search bar bisa sejajar --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    
                    {{-- Kiri: Breadcrumb & Judul --}}
                    <div class="animate-fade">
                        {{-- Breadcrumb --}}
                        <nav class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="flex items-center gap-1 hover:text-blue-600 transition-colors focus-visible"
                               aria-label="Dashboard">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Dashboard
                            </a>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            {{-- PERUBAHAN: Kata (Global) dihapus --}}
                            <span class="font-medium text-gray-900">Kelola Prestasi</span>
                        </nav>
                
                        {{-- Judul Halaman & Tombol Kembali --}}
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="group flex-shrink-0 inline-flex items-center justify-center w-11 h-11 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-md hover:shadow-lg transition-all duration-200 focus-visible"
                               aria-label="Kembali ke Dashboard">
                                <svg class="w-5 h-5 transform group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                            </a>
                            <div>
                                {{-- PERUBAHAN: Kata (Global) dihapus --}}
                                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                                    Kelola Prestasi
                                </h1>
                                <p class="text-sm text-gray-600 mt-1">
                                    Melihat data prestasi dari semua dosen
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Kanan: Search Bar --}}
                    {{-- REFAKTOR: Menambahkan search bar agar konsisten --}}
                    <div class="mt-4 flex items-center gap-3 flex-wrap">
                        <div class="relative">
                            <input type="text"
                                   id="searchInput"
                                   placeholder="Cari prestasi (Dosen, Tahun, ...)"
                                   class="w-64 sm:w-80 pl-9 pr-4 py-2.5 text-sm bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all focus-visible"
                                   onkeyup="searchPrestasi(this.value)"
                                   aria-label="Cari Prestasi">
                            <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                </div>
            </div>
        </header>
        {{-- =================================================================== --}}
        {{-- AKHIR HEADER BARU --}}
        {{-- =================================================================== --}}


        {{-- Konten Utama Dimulai Di Sini --}}
        {{-- REFAKTOR: Mengganti container agar konsisten --}}
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

            {{-- REFAKTOR: Alert diganti agar konsisten --}}
            @if(session('success'))
            <div class="mb-6 animate-slide-up">
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
                    <button onclick="this.closest('[class*=animate-slide-up]').remove()" 
                            class="flex-shrink-0 text-emerald-500 hover:text-emerald-700 transition-colors focus-visible"
                            aria-label="Tutup">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            @endif

            {{-- REFAKTOR: Menambahkan 4 Stat Card (menggantikan banner ungu) --}}
            {{-- ASUMSI: Model Prestasi punya kolom 'status' (Draft, Menunggu, Disetujui) --}}
            @php
                $totalPrestasi = method_exists($prestasi, 'total') ? $prestasi->total() : $prestasi->count();
                $statusCounts = ['total' => $totalPrestasi, 'draft' => 0, 'menunggu' => 0, 'disetujui' => 0];
                
                $sourceData = method_exists($prestasi, 'items') ? $prestasi->items() : $prestasi;

                foreach ($sourceData as $p) {
                    if (isset($p->status)) { // Jika tidak ada kolom status, ini akan di-skip
                        if ($p->status == 'Draft') $statusCounts['draft']++;
                        elseif ($p->status == 'Menunggu') $statusCounts['menunggu']++;
                        elseif ($p->status == 'Disetujui') $statusCounts['disetujui']++;
                    }
                }
            @endphp

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-6">
                {{-- Total Card --}}
                <button onclick="filterStatus('all')" 
                        class="text-left w-full bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md card-hover p-5 animate-slide-up focus-visible"
                        style="animation-delay: 0.05s">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-11 h-11 bg-white/20 rounded-lg flex items-center justify-center">
                            {{-- Icon Prestasi/Penghargaan --}}
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl sm:text-4xl font-bold text-white stat-num mb-1">{{ $statusCounts['total'] }}</p>
                    <p class="text-sm text-blue-100 font-medium">Total Prestasi</p>
                </button>

                {{-- Draft Card --}}
                <button onclick="filterStatus('draft')" 
                        class="text-left w-full bg-white hover:bg-gray-50 rounded-lg shadow-md border border-gray-200 card-hover p-5 animate-slide-up focus-visible"
                        style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-11 h-11 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl sm:text-4xl font-bold text-gray-900 stat-num mb-1">{{ $statusCounts['draft'] }}</p>
                    <p class="text-sm text-gray-600 font-medium">Draft</p>
                </button>

                {{-- Pending Card --}}
                <button onclick="filterStatus('menunggu')" 
                        class="text-left w-full {{ $statusCounts['menunggu'] > 0 ? 'bg-amber-500 hover:bg-amber-600' : 'bg-white hover:bg-gray-50' }} rounded-lg shadow-md {{ $statusCounts['menunggu'] > 0 ? '' : 'border border-gray-200' }} card-hover p-5 animate-slide-up focus-visible"
                        style="animation-delay: 0.15s">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-11 h-11 {{ $statusCounts['menunggu'] > 0 ? 'bg-white/20' : 'bg-gray-100' }} rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 {{ $statusCounts['menunggu'] > 0 ? 'text-white' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        @if($statusCounts['menunggu'] > 0)
                            <span class="px-2 py-1 bg-white/30 text-white text-xs font-semibold rounded">⚡ Butuh Aksi</span>
                        @endif
                    </div>
                    <p class="text-3xl sm:text-4xl font-bold {{ $statusCounts['menunggu'] > 0 ? 'text-white' : 'text-gray-900' }} stat-num mb-1">{{ $statusCounts['menunggu'] }}</p>
                    <p class="text-sm {{ $statusCounts['menunggu'] > 0 ? 'text-amber-50' : 'text-gray-600' }} font-medium">Menunggu Review</p>
                </button>

                {{-- Approved Card --}}
                <button onclick="filterStatus('disetujui')" 
                        class="text-left w-full bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-md card-hover p-5 animate-slide-up focus-visible"
                        style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-11 h-11 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl sm:text-4xl font-bold text-white stat-num mb-1">{{ $statusCounts['disetujui'] }}</p>
                    <p class="text-sm text-emerald-50 font-medium">Disetujui ✓</p>
                </button>
            </div>


            {{-- REFAKTOR: Mengganti wrapper tabel agar konsisten --}}
            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                
                {{-- REFAKTOR: Menambahkan header tabel + tombol filter --}}
                <div class="px-5 sm:px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Daftar Prestasi</h2>
                            <p class="text-sm text-gray-600 mt-0.5">{{ $totalPrestasi }} prestasi terdaftar</p>
                        </div>
                        
                        {{-- Filter Buttons (Hanya muncul jika ada kolom 'status') --}}
                        @if(isset($prestasi->first()->status))
                        <div class="flex flex-wrap gap-2">
                            <button onclick="filterStatus('all')" 
                                    class="filter-btn px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors focus-visible">
                                Semua
                            </button>
                            <button onclick="filterStatus('draft')" 
                                    class="filter-btn px-4 py-2 text-sm font-medium bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors focus-visible">
                                Draft
                            </button>
                            <button onclick="filterStatus('menunggu')" 
                                    class="filter-btn px-4 py-2 text-sm font-medium bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors focus-visible">
                                Menunggu
                            </button>
                            <button onclick="filterStatus('disetujui')" 
                                    class="filter-btn px-4 py-2 text-sm font-medium bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors focus-visible">
                                Disetujui
                            </button>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Tabel Tetap Dipertahankan --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dosen</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Publikasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hibah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Skor SINTA</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Buku</th>
                                {{-- REFAKTOR: Menambah kolom Status jika ada --}}
                                @if(isset($prestasi->first()->status))
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                @endif
                                {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th> --}}
                            </tr>
                        </thead>
                        {{-- REFAKTOR: Menambahkan id 'prestasiList' untuk JS --}}
                        <tbody class="bg-white divide-y divide-gray-200" id="prestasiList">
                            @forelse($prestasi as $p)
                            {{-- REFAKTOR: Menambahkan data-status untuk JS filter --}}
                            <tr class="prestasi-row" data-status="{{ isset($p->status) ? strtolower($p->status) : 'all' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $p->dosen->nama ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $p->tahun }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $p->publikasi }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($p->hibah, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $p->skor_sinta > 100 ? 'bg-green-100 text-green-800' : ($p->skor_sinta > 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ $p->skor_sinta }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $p->buku }}
                                </td>
                                {{-- REFAKTOR: Menampilkan status jika ada --}}
                                @if(isset($p->status))
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusConfig = [
                                            'Draft' => 'bg-gray-100 text-gray-700',
                                            'Menunggu' => 'bg-amber-100 text-amber-700',
                                            'Disetujui' => 'bg-emerald-100 text-emerald-700',
                                            'Ditolak' => 'bg-rose-100 text-rose-700',
                                        ];
                                        $config = $statusConfig[$p->status] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $config }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ isset($prestasi->first()->status) ? 7 : 6 }}" class="px-6 py-4 text-center text-gray-500">
                                    Belum ada data prestasi dari dosen manapun.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    {{-- REFAKTOR: Menambahkan script untuk filter & search --}}
    <script>
        // Filter by status with instant feedback
        function filterStatus(status) {
            const rows = document.querySelectorAll('.prestasi-row');
            const buttons = document.querySelectorAll('.filter-btn');
            const searchInput = document.getElementById('searchInput');
            
            if (searchInput) searchInput.value = '';
            
            buttons.forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-700');
            });
            
            const targetButton = Array.from(buttons).find(btn => btn.getAttribute('onclick') === `filterStatus('${status}')`);
            
            if (targetButton) {
                targetButton.classList.add('bg-blue-600', 'text-white');
                targetButton.classList.remove('bg-gray-100', 'text-gray-700');
            } else if (status === 'all') {
                buttons[0].classList.add('bg-blue-600', 'text-white');
                buttons[0].classList.remove('bg-gray-100', 'text-gray-700');
            }
            
            rows.forEach(row => {
                const rowStatus = row.dataset.status;
                const isMatch = (status === 'all' || rowStatus === status);
                row.style.display = isMatch ? '' : 'none'; // Menggunakan display default tabel (bukan 'block')
            });
        }
        
        // Search functionality
        function searchPrestasi(query) {
            const rows = document.querySelectorAll('.prestasi-row');
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
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Set filter 'all' on page load, jika tombol filter ada
        document.addEventListener('DOMContentLoaded', () => {
            if (document.querySelectorAll('.filter-btn').length > 0) {
                filterStatus('all');
            }
        });
    </script>
</x-app-layout>