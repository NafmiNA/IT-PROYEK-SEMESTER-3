<x-app-layout>
    {{-- Style CSS (Tetap ada untuk header dan bg-subtle) --}}
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

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 bg-subtle">
        
        {{-- =================================================================== --}}
        {{-- HEADER --}}
        {{-- =================================================================== --}}
        <header class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
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
                            <span class="font-medium text-gray-900">Kelola Prestasi</span>
                        </nav>
                
                        {{-- Judul Halaman & Tombol Kembali --}}
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="group flex-shrink-0 inline-flex items-center justify-center w-11 h-11 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-md hover:shadow-lg transition-all duration-200 focus-visible"
                               style="background-color: #2563eb !important; color: white !important;"
                               aria-label="Kembali ke Dashboard">
                                <svg class="w-5 h-5 transform group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                            </a>
                            <div>
                                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                                    Kelola Prestasi
                                </h1>
                                <p class="text-sm text-gray-600 mt-1">
                                    Melihat data prestasi dari semua dosen
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Kanan: Search Bar & Tombol --}}
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

                        {{-- Tombol Kelola Bobot --}}
                        <a href="{{ route('admin.ahp.index') }}" 
                           class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all focus-visible"
                           style="background-color: #9333ea; color: white;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Kelola Bobot
                        </a>

                        {{-- Tombol Hitung Ranking (SAW) --}}
                        <a href="{{ route('admin.saw.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-sm font-semibold rounded-lg shadow-md transition-all duration-200"
                           style="background: linear-gradient(to right, #9333ea, #4f46e5); color: white;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Hitung Ranking (SAW)
                        </a>
                    </div>

                </div>
            </div>
        </header>
        {{-- =================================================================== --}}
        {{-- AKHIR HEADER BARU --}}
        {{-- =================================================================== --}}


        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

            {{-- Alert --}}
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

            {{-- Bobot Kriteria Cards --}}
            @if($bobot->isNotEmpty())
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Bobot Kriteria Penilaian</h2>
                    @if($bobot->first()['is_consistent'])
                        <span class="text-xs px-3 py-1 bg-green-100 text-green-700 rounded-full font-medium">
                            Konsisten (CR: {{ number_format($bobot->first()['consistency_ratio'], 4) }})
                        </span>
                    @else
                        <span class="text-xs px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full font-medium">
                            Perlu Revisi (CR: {{ number_format($bobot->first()['consistency_ratio'], 4) }})
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($bobot as $b)
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-bold">
                                    {{ $b['kode'] }}
                                </span>
                                <span class="text-sm font-medium text-gray-700">{{ $b['nama'] }}</span>
                            </div>
                        </div>
                        <div class="flex items-end justify-between">
                            <span class="text-3xl font-bold text-gray-900">{{ number_format($b['bobot_percent'], 1) }}%</span>
                            <span class="text-xs text-gray-500">Bobot: {{ number_format($b['bobot'], 4) }}</span>
                        </div>
                        <div class="mt-3">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $b['bobot_percent'] }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="mb-6">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-yellow-900 text-sm">Bobot Kriteria Belum Dihitung</p>
                            <p class="text-sm text-yellow-700 mt-1">
                                Silakan klik tombol <span class="font-semibold">"Kelola Bobot"</span> untuk menentukan bobot kriteria menggunakan metode AHP.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Wrapper Tabel --}}
            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                
                {{-- Header Tabel --}}
                <div class="px-5 sm:px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Daftar Prestasi</h2>
                            <p class="text-sm text-gray-600 mt-0.5">{{ method_exists($prestasi, 'total') ? $prestasi->total() : $prestasi->count() }} prestasi terdaftar</p>
                        </div>
                        
                        {{-- =================================================== --}}
                        {{-- PERUBAHAN: Tombol Filter DIHAPUS --}}
                        {{-- =================================================== --}}
                    </div>
                </div>

                {{-- Tabel --}}
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
                                {{-- =================================================== --}}
                                {{-- PERUBAHAN: Kolom Status DIHAPUS --}}
                                {{-- =================================================== --}}
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="prestasiList">
                            @forelse($prestasi as $p)
                            {{-- PERUBAHAN: data-status DIHAPUS --}}
                            <tr class="prestasi-row">
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
                                {{-- =================================================== --}}
                                {{-- PERUBAHAN: Kolom Status <td> DIHAPUS --}}
                                {{-- =================================================== --}}
                            </tr>
                            @empty
                            <tr>
                                {{-- PERUBAHAN: Colspan diubah ke 6 --}}
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
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

    {{-- =================================================== --}}
    {{-- PERUBAHAN: Script disederhanakan --}}
    {{-- =================================================== --}}
    <script>
        // Search functionality
        function searchPrestasi(query) {
            const rows = document.querySelectorAll('.prestasi-row');
            const searchTerm = query.toLowerCase().trim();

            // Filter rows based on search
            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchTerm)) {
                    row.style.display = ''; // Menampilkan baris (default tabel)
                } else {
                    row.style.display = 'none'; // Menyembunyikan baris
                }
            });
        }
    </script>
</x-app-layout>