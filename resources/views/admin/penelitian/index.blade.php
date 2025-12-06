<x-app-layout>
    <style>
        /* Performance-optimized animations <400ms (Doherty Threshold) */
        @keyframes slideUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
        
        .animate-slide-up { animation: slideUp 0.3s ease-out both; }
        .animate-fade { animation: fadeIn 0.4s ease-out both; }
        
        /* Hover effects with proper timing */
        .card-hover { 
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover { 
            transform: translateY(-4px); 
            box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.15), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
        }
        
        /* Accessible focus states */
        .focus-visible:focus-visible { 
            outline: 3px solid #3b82f6; 
            outline-offset: 2px; 
        }
        
        /* Subtle background pattern */
        .bg-subtle {
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(59, 130, 246, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 90% 80%, rgba(147, 51, 234, 0.04) 0%, transparent 50%);
        }
        
        /* Stat number animation */
        .stat-num {
            transition: transform 0.3s ease;
        }
        .card-hover:hover .stat-num {
            transform: scale(1.08);
        }

        /* Consistent, tidy action buttons */
        .action-group { display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap; justify-content:flex-end; }
        .action-btn {
            display:inline-flex; align-items:center; justify-content:center; gap:0.375rem;
            height:36px; padding:0 12px; border-radius:10px; font-weight:600; font-size:0.875rem; white-space:nowrap;
            box-shadow: 0 1px 1px rgba(0,0,0,0.04);
            transition-colors: 0.2s ease;
        }
        .action-btn svg { width:16px; height:16px; }

        /* REFAKTOR: Kelas tombol dipusatkan di sini untuk konsistensi */
        .btn-detail { background-color:#3b82f6; color:#fff; } /* blue-600 */
        .btn-detail:hover { background-color:#2563eb; } /* blue-700 */
        
        .btn-edit { background-color:#f97316; color:#fff; } /* orange-500 */
        .btn-edit:hover { background-color:#ea580c; } /* orange-600 */

        .btn-approve { background-color:#16a34a; color:#fff; } /* green-600 */
        .btn-approve:hover { background-color:#15803d; } /* green-700 */

        .btn-reject { background-color:#4b5563; color:#fff; } /* gray-600 */
        .btn-reject:hover { background-color:#374151; } /* gray-700 */
        
        .btn-delete { background-color:#dc2626; color:#fff; } /* red-600 */
        .btn-delete:hover { background-color:#b91c1c; } /* red-700 */
    </style>

    {{-- Clean background with subtle pattern --}}
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 bg-subtle">
        
        {{-- Header with breadcrumb (Jakob's Law - familiar pattern) --}}
        <header class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    
                    {{-- Left: Breadcrumb & Title (Serial Position Effect) --}}
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
                            <span class="font-medium text-gray-900">Kelola Penelitian</span>
                        </nav>

                        {{-- Title with back button (Fitts's Law - adequate size) --}}
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="group flex-shrink-0 inline-flex items-center justify-center w-11 h-11 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-md hover:shadow-lg transition-all duration-200 focus-visible"
                               aria-label="Kembali ke Dashboard">
                                <svg class="w-5 h-5 transform group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                            </a>
                            
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Kelola Penelitian</h1>
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-semibold rounded">Live</span>
                                </div>
                                <p class="text-sm text-gray-600">Pantau dan kelola semua penelitian</p>
                            </div>
                        </div>
                    </div>

                    {{-- Toolbar: Search & ADD BUTTON (Sudah Ditambahkan Kembali) --}}
                    <div class="mt-4 flex items-center gap-3 flex-wrap">
                        <div class="relative">
                            <input type="text"
                                   id="searchInput"
                                   placeholder="Cari penelitian..."
                                   class="w-64 sm:w-80 pl-9 pr-4 py-2.5 text-sm bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all focus-visible"
                                   onkeyup="searchPenelitian(this.value)"
                                   aria-label="Cari penelitian">
                            <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        
                        {{-- TOMBOL EXPORT --}}
                        <a href="{{ route('admin.penelitian.export') }}" 
                           class="inline-flex items-center justify-center px-4 py-2.5 bg-green-800 hover:bg-green-900 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow transition-all focus-visible">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Export Excel
                        </a>
                        
                        {{-- TOMBOL TAMBAH PENELITIAN --}}
                        <a href="{{ route('admin.penelitian.create') }}" 
                           class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow transition-all focus-visible">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Penelitian
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            
            {{-- Success Alert (Von Restorff Effect) --}}
            @if (session('success'))
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

            {{-- Stats Cards (Miller's Law - 4 items max) --}}
            @php
                $totalPenelitian = method_exists($penelitian, 'total') ? $penelitian->total() : $penelitian->count();
                $statusCounts = ['total' => $totalPenelitian, 'draft' => 0, 'menunggu' => 0, 'disetujui' => 0];
                
                $sourceData = method_exists($penelitian, 'items') ? $penelitian->items() : $penelitian;

                foreach ($sourceData as $p) {
                    if (isset($p->status)) {
                        if ($p->status == 'Draft') $statusCounts['draft']++;
                        elseif ($p->status == 'Menunggu') $statusCounts['menunggu']++;
                        elseif ($p->status == 'Disetujui') $statusCounts['disetujui']++;
                    }
                }
            @endphp

            <div class="grid grid-cols-2 gap-4 sm:gap-5 mb-6">
                {{-- Total Card --}}
                <div class="text-left w-full bg-white rounded-lg shadow-md border border-gray-200 p-5 animate-slide-up"
                     style="animation-delay: 0.05s">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-11 h-11 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl sm:text-4xl font-bold text-gray-900 stat-num mb-1">{{ $statusCounts['total'] }}</p>
                    <p class="text-sm text-gray-600 font-medium">Total Penelitian</p>
                </div>

                {{-- Draft Card --}}
                <div class="text-left w-full bg-white rounded-lg shadow-md border border-gray-200 p-5 animate-slide-up"
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
                </div>
            </div>

            {{-- Main Content (Law of Common Region) --}}
            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                
                {{-- Header with Filters (Hick's Law - limited options) --}}
                <div class="px-5 sm:px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Daftar Penelitian</h2>
                            <p class="text-sm text-gray-600 mt-0.5">{{ $totalPenelitian }} penelitian terdaftar</p>
                        </div>
                    </div>
                </div>

                {{-- Penelitian List (Law of Proximity - grouped elements) --}}
                <div class="p-5 sm:p-6">
                    <div class="space-y-4" id="penelitianList">
                        @forelse ($penelitian as $index => $p)
                            @php
                                $statusConfig = [
                                    'Draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => 'text-gray-600'],
                                    'Menunggu' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'icon' => 'text-amber-600'],
                                    'Disetujui' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'icon' => 'text-emerald-600'],
                                    'Ditolak' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-700', 'icon' => 'text-rose-600'],
                                ];
                                $status = $p->status ?? 'Draft';
                                $config = $statusConfig[$status] ?? $statusConfig['Draft'];
                            @endphp

                            {{-- Card (Law of Similarity - consistent design) --}}
                            <div class="penelitian-card border border-gray-200 rounded-lg p-4 sm:p-5 hover:border-blue-300 hover:shadow-md transition-all duration-200 animate-slide-up"
                                 style="animation-delay: {{ min($index * 0.03, 0.5) }}s"
                                 data-status="{{ strtolower($status) }}">
                                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                                    
                                    {{-- Content (Law of Proximity) --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start gap-3 mb-3">
                                            {{-- Icon --}}
                                            <div class="flex-shrink-0 w-11 h-11 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-lg shadow-sm">
                                                {{ strtoupper(substr($p->judul, 0, 1)) }}
                                            </div>
                                            
                                            {{-- Title & Meta --}}
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 line-clamp-2">
                                                    {{ $p->judul }}
                                                </h3>
                                                <div class="flex flex-wrap items-center gap-2.5 text-sm text-gray-600">
                                                    <span class="inline-flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                        {{ $p->tahun }}
                                                    </span>
                                                    @if($p->skema)
                                                        <span class="inline-flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                            </svg>
                                                            {{ $p->skema }}
                                                        </span>
                                                    @endif
                                                    <span class="text-xs text-gray-500">
                                                        {{ $p->updated_at?->diffForHumans() ?? $p->created_at?->diffForHumans() }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Action Buttons (tidy & consistent) --}}
                                    <div class="action-group flex-shrink-0">
                                        <a href="{{ route('admin.penelitian.show', $p) }}" 
                                           class="action-btn btn-detail focus-visible">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Detail
                                        </a>

                                        <a href="{{ route('admin.penelitian.edit', $p) }}" 
                                           class="action-btn btn-edit focus-visible">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.penelitian.destroy', $p) }}" method="POST" 
                                              onsubmit="return confirm('Yakin ingin menghapus penelitian ini?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="action-btn btn-delete focus-visible">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            {{-- Empty State (provides guidance) --}}
                            <div class="text-center py-16 animate-fade">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-5">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Penelitian</h3>
                                <p class="text-gray-600 mb-6 max-w-sm mx-auto">
                                    Saat ini belum ada data penelitian yang terdaftar.
                                </p>
                                <a href="{{ route('admin.penelitian.create') }}"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all focus-visible">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Buat Penelitian Pertama
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Pagination --}}
                @if (method_exists($penelitian, 'links') && $penelitian->lastPage() > 1)
                    <div class="px-5 sm:px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <p class="text-sm text-gray-600">
                                Menampilkan <span class="font-semibold">{{ $penelitian->firstItem() ?? 0 }}</span> - 
                                <span class="font-semibold">{{ $penelitian->lastItem() ?? 0 }}</span> dari 
                                <span class="font-semibold">{{ $penelitian->total() }}</span> penelitian
                            </p>
                            <div>
                                {{ $penelitian->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Footer Spacing --}}
            <div class="h-8"></div>
        </main>
    </div>

    {{-- Optimized JavaScript (Doherty Threshold) --}}
    <script>
        // Filter by status with instant feedback
        function filterStatus(status) {
            const cards = document.querySelectorAll('.penelitian-card');
            const buttons = document.querySelectorAll('.filter-btn');
            const searchInput = document.getElementById('searchInput');
            
            // Clear search
            if (searchInput) searchInput.value = '';
            
            // Update button states (Law of Similarity)
            buttons.forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-700');
            });
            
            // REFAKTOR: Logika highlighting tombol diperbaiki
            const targetButton = Array.from(buttons).find(btn => btn.getAttribute('onclick') === `filterStatus('${status}')`);
            
            if (targetButton) {
                targetButton.classList.add('bg-blue-600', 'text-white');
                targetButton.classList.remove('bg-gray-100', 'text-gray-700');
            } else if (status === 'all') {
                buttons[0].classList.add('bg-blue-600', 'text-white');
                buttons[0].classList.remove('bg-gray-100', 'text-gray-700');
            }
            
            // Filter cards with animation
            let visibleCount = 0;
            cards.forEach(card => {
                const cardStatus = card.dataset.status;
                const isMatch = (status === 'all' || cardStatus === status);

                if (isMatch) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
        }
        
        // Search functionality
        function searchPenelitian(query) {
            const cards = document.querySelectorAll('.penelitian-card');
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

            // Filter cards based on search
            cards.forEach(card => {
                const title = card.querySelector('h3') ? card.querySelector('h3').textContent.toLowerCase() : '';
                
                // REFAKTOR: Perbaikan typo 'class*al' -> 'class*='
                const yearSpan = card.querySelector('span[class*="gap-1"]');
                const year = yearSpan ? yearSpan.textContent.toLowerCase() : '';

                if (title.includes(searchTerm) || year.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</x-app-layout>