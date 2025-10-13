{{-- resources/views/dosen/penelitian/index.blade.php --}}
{{-- Redesigned with 15 Laws of UX principles --}}
<x-app-layout>
    <style>
        /* Doherty Threshold - Fast animations <400ms */
        @keyframes slideInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .animate-slide-up { animation: slideInUp 0.3s ease-out; }
        .animate-fade-in { animation: fadeIn 0.3s ease-out; }
        .hover-card { transition: all 0.2s ease; }
        .hover-card:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); }
        .focus-ring:focus-visible { outline: 3px solid #2563eb; outline-offset: 2px; border-radius: 0.5rem; }
    </style>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-purple-50 bg-pattern relative overflow-hidden">
        {{-- Decorative animated background elements --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-float"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-float" style="animation-delay: 1s;"></div>
            <div class="absolute top-40 left-1/2 w-80 h-80 bg-cyan-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float" style="animation-delay: 2s;"></div>
        </div>

        {{-- Header Section - Jakob's Law (familiar top bar) --}}
        <header class="relative bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    {{-- Title Section with Breadcrumb - Serial Position Effect --}}
                    <div class="animate-fade-in">
                        {{-- Breadcrumb Navigation --}}
                        <div class="flex items-center gap-2 mb-3 text-sm text-gray-600">
                            <a href="{{ route('dosen.dashboard') }}" class="hover:text-blue-600 transition flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Dashboard
                            </a>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span class="text-gray-900 font-medium">Kelola Penelitian</span>
                        </div>

                        <div class="flex items-center gap-4">
                            {{-- Rollback Button to Dashboard --}}
                            <a href="{{ route('dosen.dashboard') }}" 
                               class="group inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg hover:shadow-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-110 focus-ring"
                               title="Kembali ke Dashboard">
                                <svg class="w-6 h-6 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                            </a>
                            
                            <div>
                                <p class="text-xs uppercase tracking-wider font-bold gradient-text mb-1">📊 Manajemen Penelitian</p>
                                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center gap-2">
                                    Kelola Penelitian
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 animate-pulse-slow">
                                        Live
                                    </span>
                                </h1>
                                <p class="text-sm text-gray-600 mt-1">Pantau dan kelola seluruh proposal penelitian Anda</p>
                            </div>
                        </div>
                    </div>

                    {{-- Action Group --}}
                    <div class="flex items-center gap-3">
                        {{-- Search Bar - Pareto Principle (80/20 usage) --}}
                        <div class="relative group">
                            <input type="text" 
                                   id="searchInput"
                                   placeholder="Cari penelitian..." 
                                   class="w-full sm:w-64 pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                   onkeyup="searchPenelitian(this.value)">
                            <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400 group-focus-within:text-blue-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>

                        {{-- Primary Action - Fitts's Law --}}
                        <a href="{{ route('dosen.penelitian.create') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 via-blue-700 to-purple-600 px-6 py-3 text-sm font-semibold text-white shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 focus-ring relative overflow-hidden group">
                            <span class="absolute inset-0 shimmer-bg"></span>
                            <svg class="w-5 h-5 relative z-10 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span class="relative z-10">Tambah Penelitian</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <main class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            
            {{-- Success Message - Von Restorff Effect (distinctive) --}}
            @if (session('success'))
                <div class="mb-6 animate-slide-up">
                    <div class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 shadow-md">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-emerald-900">Berhasil!</p>
                            <p class="text-sm text-emerald-700">{{ session('success') }}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            {{-- Stats Overview - Miller's Law (4 items, easy to remember) --}}
            @php
                $totalPenelitian = method_exists($penelitian, 'total') ? $penelitian->total() : $penelitian->count();
                $statusCounts = [
                    'total' => $totalPenelitian,
                    'draft' => 0,
                    'pending' => 0,
                    'approved' => 0
                ];
                // Simple count if collection
                if (!method_exists($penelitian, 'total')) {
                    foreach ($penelitian as $p) {
                        if (isset($p->status)) {
                            if ($p->status == 'Draft') $statusCounts['draft']++;
                            elseif ($p->status == 'Menunggu') $statusCounts['pending']++;
                            elseif ($p->status == 'Disetujui') $statusCounts['approved']++;
                        }
                    }
                }
            @endphp

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                {{-- Total Card with Enhanced Design --}}
                <div class="relative bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl shadow-lg hover-card p-5 border border-blue-400 animate-slide-up overflow-hidden group cursor-pointer" 
                     style="animation-delay: 0.1s"
                     onclick="filterStatus('all')">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-3">
                            <div class="h-12 w-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="h-8 w-8 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-white stat-number mb-1">{{ $statusCounts['total'] }}</p>
                        <p class="text-sm text-blue-100 font-medium">Total Penelitian</p>
                        <div class="mt-3 pt-3 border-t border-white/20">
                            <p class="text-xs text-blue-50">Klik untuk lihat semua</p>
                        </div>
                    </div>
                </div>

                {{-- Draft Card --}}
                <div class="relative bg-white rounded-xl shadow-md hover-card p-5 border-l-4 border-gray-400 animate-slide-up overflow-hidden group cursor-pointer" 
                     style="animation-delay: 0.2s"
                     onclick="filterStatus('draft')">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-gray-100 rounded-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-2">
                            <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center transform group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 stat-number">{{ $statusCounts['draft'] }}</p>
                        <p class="text-sm text-gray-600 mt-1">Draft</p>
                        <p class="text-xs text-gray-400 mt-2">Perlu diselesaikan</p>
                    </div>
                </div>

                {{-- Pending Card - Von Restorff Effect --}}
                <div class="relative {{ $statusCounts['pending'] > 0 ? 'bg-gradient-to-br from-amber-400 to-amber-600' : 'bg-white' }} rounded-xl shadow-md hover-card p-5 border-l-4 {{ $statusCounts['pending'] > 0 ? 'border-amber-500' : 'border-gray-300' }} animate-slide-up overflow-hidden group cursor-pointer" 
                     style="animation-delay: 0.3s"
                     onclick="filterStatus('pending')">
                    @if($statusCounts['pending'] > 0)
                        <div class="absolute inset-0">
                            <div class="absolute inset-0 bg-gradient-to-br from-amber-500 to-amber-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="absolute top-0 right-0 -mr-4 -mt-4">
                                <div class="w-16 h-16 bg-white/10 rounded-full animate-pulse-slow"></div>
                            </div>
                        </div>
                    @endif
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-2">
                            <div class="h-10 w-10 rounded-lg {{ $statusCounts['pending'] > 0 ? 'bg-white/20 backdrop-blur-sm' : 'bg-gray-100' }} flex items-center justify-center transform group-hover:scale-110 group-hover:rotate-12 transition-all">
                                <svg class="w-6 h-6 {{ $statusCounts['pending'] > 0 ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            @if($statusCounts['pending'] > 0)
                                <span class="px-3 py-1 bg-white/30 backdrop-blur-sm text-white text-xs font-bold rounded-full border border-white/50 animate-bounce-subtle">
                                    🔔 Perlu Review
                                </span>
                            @endif
                        </div>
                        <p class="text-3xl font-bold {{ $statusCounts['pending'] > 0 ? 'text-white' : 'text-gray-900' }} stat-number">{{ $statusCounts['pending'] }}</p>
                        <p class="text-sm {{ $statusCounts['pending'] > 0 ? 'text-amber-50' : 'text-gray-600' }} mt-1 font-medium">Menunggu Verifikasi</p>
                        @if($statusCounts['pending'] > 0)
                            <div class="mt-3 pt-3 border-t border-white/20">
                                <p class="text-xs text-white/90">Segera tinjau!</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Approved Card --}}
                <div class="relative bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-xl shadow-lg hover-card p-5 border border-emerald-400 animate-slide-up overflow-hidden group cursor-pointer" 
                     style="animation-delay: 0.4s"
                     onclick="filterStatus('approved')">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-400 to-emerald-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-white/10 rounded-full"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-3">
                            <div class="h-12 w-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center transform group-hover:scale-110 group-hover:-rotate-6 transition-all duration-300">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="text-white text-lg">✓</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-white stat-number mb-1">{{ $statusCounts['approved'] }}</p>
                        <p class="text-sm text-emerald-50 font-medium">Disetujui</p>
                        <div class="mt-3 pt-3 border-t border-white/20">
                            <p class="text-xs text-emerald-50">Sukses disetujui! 🎉</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content Section --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200">
                {{-- Section Header with Filters - Law of Common Region --}}
                <div class="border-b border-gray-200 bg-gradient-to-r from-gray-50 to-blue-50/30 px-6 py-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Daftar Penelitian</h2>
                            <p class="text-sm text-gray-600">{{ $totalPenelitian }} penelitian terdaftar</p>
                        </div>
                        
                        {{-- Quick Filters - Hick's Law (limited choices) --}}
                        <div class="flex flex-wrap gap-2">
                            <button onclick="filterStatus('all')" 
                                    class="filter-btn px-4 py-2 text-sm font-medium rounded-lg bg-blue-600 text-white transition hover:bg-blue-700 focus-ring">
                                Semua
                            </button>
                            <button onclick="filterStatus('draft')" 
                                    class="filter-btn px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 text-gray-700 transition hover:bg-gray-200 focus-ring">
                                Draft
                            </button>
                            <button onclick="filterStatus('pending')" 
                                    class="filter-btn px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 text-gray-700 transition hover:bg-gray-200 focus-ring">
                                Menunggu
                            </button>
                            <button onclick="filterStatus('approved')" 
                                    class="filter-btn px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 text-gray-700 transition hover:bg-gray-200 focus-ring">
                                Disetujui
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Penelitian List - Law of Proximity (card-based grouping) --}}
                <div class="p-6">
                    <div class="space-y-4" id="penelitianList">
                        @forelse ($penelitian as $index => $p)
                            @php
                                $statusConfig = [
                                    'Draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-300'],
                                    'Menunggu' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'border' => 'border-amber-300'],
                                    'Disetujui' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' => 'border-emerald-300'],
                                    'Ditolak' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-700', 'border' => 'border-rose-300'],
                                ];
                                $status = $p->status ?? 'Draft';
                                $config = $statusConfig[$status] ?? $statusConfig['Draft'];
                            @endphp

                            {{-- Research Card - Law of Similarity (consistent design) --}}
                            <div class="penelitian-card border border-gray-200 rounded-xl p-5 hover:border-blue-300 hover:shadow-md transition-all duration-200 animate-slide-up"
                                 style="animation-delay: {{ $index * 0.05 }}s"
                                 data-status="{{ strtolower($status) }}">
                                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                                    {{-- Content Section - Law of Proximity --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start gap-3 mb-3">
                                            {{-- Icon --}}
                                            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                                {{ strtoupper(substr($p->judul, 0, 1)) }}
                                            </div>
                                            
                                            {{-- Title & Meta --}}
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-lg font-semibold text-gray-900 mb-1 line-clamp-2">
                                                    {{ $p->judul }}
                                                </h3>
                                                <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600">
                                                    <span class="inline-flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                        Tahun {{ $p->tahun }}
                                                    </span>
                                                    @if($p->skema)
                                                        <span class="inline-flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                            </svg>
                                                            {{ $p->skema }}
                                                        </span>
                                                    @endif
                                                    <span class="text-xs text-gray-400">
                                                        Diupdate {{ $p->updated_at?->diffForHumans() ?? $p->created_at?->diffForHumans() }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Status Badge - Goal-Gradient Effect (show progress) --}}
                                        <div class="flex items-center gap-2 ml-15">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }} border {{ $config['border'] }}">
                                                @if($status == 'Disetujui')
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                                    </svg>
                                                @elseif($status == 'Menunggu')
                                                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/>
                                                    </svg>
                                                @elseif($status == 'Ditolak')
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                    </svg>
                                                @endif
                                                {{ $status }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Action Buttons - Fitts's Law (adequate size), Pareto Principle (common actions) --}}
                                    <div class="flex flex-wrap lg:flex-col gap-2">
                                        {{-- View Button --}}
                                        <a href="{{ route('dosen.penelitian.show', $p) }}" 
                                           class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition focus-ring">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Detail
                                        </a>

                                        {{-- Edit Button --}}
                                        <a href="{{ route('dosen.penelitian.edit', $p) }}" 
                                           class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-amber-700 bg-amber-50 rounded-lg hover:bg-amber-100 transition focus-ring">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>

                                        {{-- Delete Button --}}
                                        <form action="{{ route('dosen.penelitian.destroy', $p) }}" method="POST" 
                                              onsubmit="return confirm('Yakin ingin menghapus penelitian ini? Tindakan ini tidak dapat dibatalkan.');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-rose-700 bg-rose-50 rounded-lg hover:bg-rose-100 transition focus-ring">
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
                            {{-- Empty State - Provides helpful guidance --}}
                            <div class="text-center py-16 animate-fade-in">
                                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 mb-6">
                                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Penelitian</h3>
                                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                                    Mulai perjalanan riset Anda dengan menambahkan proposal penelitian pertama. Kami siap membantu Anda!
                                </p>
                                <a href="{{ route('dosen.penelitian.create') }}"
                                   class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105 focus-ring">
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
                    <div class="border-t border-gray-200 px-6 py-4 bg-gray-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
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
            <div class="h-12"></div>
        </main>
    </div>

    {{-- Enhanced Filter & Search Script --}}
    <script>
        // Filter by status - with button highlight
        function filterStatus(status) {
            const cards = document.querySelectorAll('.penelitian-card');
            const buttons = document.querySelectorAll('.filter-btn');
            const searchInput = document.getElementById('searchInput');
            
            // Clear search when filtering
            if (searchInput) searchInput.value = '';
            
            // Update button states - Law of Similarity
            buttons.forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-700');
            });
            
            // Find and highlight clicked button
            if (event && event.target) {
                event.target.classList.remove('bg-gray-100', 'text-gray-700');
                event.target.classList.add('bg-blue-600', 'text-white');
            }
            
            // Filter cards - Doherty Threshold (instant feedback)
            let visibleCount = 0;
            cards.forEach((card, index) => {
                const cardStatus = card.dataset.status;
                if (status === 'all' || cardStatus === status) {
                    card.style.display = '';
                    visibleCount++;
                    // Re-animate visible cards
                    card.style.animation = 'none';
                    setTimeout(() => {
                        card.style.animation = `slideInUp 0.3s ease-out ${visibleCount * 0.05}s`;
                    }, 10);
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Show count feedback
            showFilterFeedback(visibleCount, status);
        }
        
        // Search penelitian by title
        function searchPenelitian(query) {
            const cards = document.querySelectorAll('.penelitian-card');
            const buttons = document.querySelectorAll('.filter-btn');
            
            // Reset filter buttons
            buttons.forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-700');
            });
            buttons[0].classList.remove('bg-gray-100', 'text-gray-700');
            buttons[0].classList.add('bg-blue-600', 'text-white');
            
            const searchTerm = query.toLowerCase().trim();
            let visibleCount = 0;
            
            cards.forEach((card, index) => {
                const title = card.querySelector('h3').textContent.toLowerCase();
                
                if (searchTerm === '' || title.includes(searchTerm)) {
                    card.style.display = '';
                    visibleCount++;
                    // Animate matching cards
                    card.style.animation = 'none';
                    setTimeout(() => {
                        card.style.animation = `slideInUp 0.3s ease-out ${visibleCount * 0.05}s`;
                    }, 10);
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Show search feedback
            showFilterFeedback(visibleCount, 'search');
        }
        
        // Visual feedback for filtering
        function showFilterFeedback(count, type) {
            const existingFeedback = document.getElementById('filterFeedback');
            if (existingFeedback) existingFeedback.remove();
            
            const feedback = document.createElement('div');
            feedback.id = 'filterFeedback';
            feedback.className = 'fixed bottom-6 right-6 bg-blue-600 text-white px-4 py-3 rounded-xl shadow-lg animate-slide-up z-50';
            feedback.innerHTML = `
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span><strong>${count}</strong> penelitian ditemukan</span>
                </div>
            `;
            
            document.body.appendChild(feedback);
            setTimeout(() => {
                feedback.style.transition = 'opacity 0.3s ease';
                feedback.style.opacity = '0';
                setTimeout(() => feedback.remove(), 300);
            }, 2000);
        }
        
        // Keyboard shortcut: Ctrl/Cmd + K to focus search
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                document.getElementById('searchInput')?.focus();
            }
        });
    </script>
</x-app-layout>
