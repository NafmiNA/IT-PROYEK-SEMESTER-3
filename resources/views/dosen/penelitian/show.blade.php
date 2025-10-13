<x-app-layout>
    @php
        $anggota = $penelitian->dosens->where('pivot.peran', 'Anggota');
        $pendukung = $penelitian->relationLoaded('mahasiswas') ? $penelitian->mahasiswas : collect();
        
        // Status configuration (Law of Similarity)
        $statusConfig = [
            'Disetujui' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'icon' => 'check-circle'],
            'Menunggu' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'icon' => 'clock'],
            'Draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => 'document-text'],
        ];
        $status = $penelitian->status ?? 'Draft';
        $config = $statusConfig[$status] ?? $statusConfig['Draft'];
    @endphp

    {{-- Modern UX-focused Layout (15 Laws Applied) --}}
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50/30 to-gray-50">
        <div class="mx-auto max-w-7xl px-4 py-8">
            
            {{-- Breadcrumb Navigation (Jakob's Law - familiar patterns) --}}
            <nav class="mb-6 animate-fade">
                <ol class="flex items-center gap-2 text-sm text-gray-600">
                    <li><a href="{{ route('dosen.dashboard') }}" class="hover:text-blue-600 transition-colors">Dashboard</a></li>
                    <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                    <li><a href="{{ route('dosen.penelitian.index') }}" class="hover:text-blue-600 transition-colors">Penelitian</a></li>
                    <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                    <li class="font-semibold text-blue-600">Detail</li>
                </ol>
            </nav>

            {{-- Header with Actions (Fitts's Law + Serial Position Effect) --}}
            <div class="mb-8 animate-slide-up">
                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                    {{-- Title Section --}}
                    <div class="flex items-start gap-3 flex-1">
                        <a href="{{ route('dosen.penelitian.index') }}" 
                           class="group flex-shrink-0 inline-flex items-center justify-center w-11 h-11 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-md hover:shadow-lg transition-all duration-200"
                           aria-label="Kembali ke Daftar">
                            <svg class="w-5 h-5 transform group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </a>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <p class="text-xs uppercase tracking-wider text-blue-600/70 font-semibold">Detail Penelitian</p>
                                {{-- Status Badge (Law of Similarity) --}}
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 {{ $config['bg'] }} {{ $config['text'] }} text-xs font-semibold rounded-full">
                                    @if($status == 'Disetujui')
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                        </svg>
                                    @elseif($status == 'Menunggu')
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/>
                                        </svg>
                                    @endif
                                    {{ $status }}
                                </span>
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">{{ $penelitian->judul }}</h1>
                            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $penelitian->created_at?->format('d M Y') ?? '-' }}
                                </span>
                                <span class="text-gray-300">•</span>
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Ketua: {{ $penelitian->ketua->nama ?? 'Tidak diketahui' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons (Fitts's Law - adequate size) --}}
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('dosen.penelitian.edit', $penelitian) }}" 
                           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold shadow-md hover:shadow-lg transition-all whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Data
                        </a>
                    </div>
                </div>
            </div>

            {{-- Content Grid (Law of Common Region + Law of Proximity) --}}
            <div class="space-y-6 animate-fade">
                
                {{-- Row 1: Basic Info + Team (Law of Proximity - related info grouped) --}}
                <div class="grid gap-6 lg:grid-cols-2">
                    
                    {{-- Basic Information Card --}}
                    <article class="rounded-2xl bg-white shadow-lg ring-1 ring-gray-200 overflow-hidden transition-all hover:shadow-xl">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h2 class="text-lg font-bold text-white">Informasi Utama</h2>
                            </div>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-4">
                                <div class="flex items-start justify-between gap-4 pb-4 border-b border-gray-100">
                                    <dt class="text-sm font-medium text-gray-600 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Tahun
                                    </dt>
                                    <dd class="text-sm font-bold text-gray-900">{{ $penelitian->tahun }}</dd>
                                </div>
                                <div class="flex items-start justify-between gap-4 pb-4 border-b border-gray-100">
                                    <dt class="text-sm font-medium text-gray-600 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        Skema
                                    </dt>
                                    <dd class="text-sm font-bold text-gray-900 text-right">{{ $penelitian->skema ?? '—' }}</dd>
                                </div>
                                <div class="flex items-start justify-between gap-4 pb-4 border-b border-gray-100">
                                    <dt class="text-sm font-medium text-gray-600 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        Sumber Dana
                                    </dt>
                                    <dd class="text-sm font-bold text-gray-900 text-right">{{ $penelitian->sumber_dana ?? '—' }}</dd>
                                </div>
                                <div class="flex items-start justify-between gap-4 pb-4 border-b border-gray-100">
                                    <dt class="text-sm font-medium text-gray-600 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Total Dana
                                    </dt>
                                    <dd class="text-sm font-bold text-blue-600">
                                        {{ $penelitian->dana ? 'Rp '.number_format($penelitian->dana, 0, ',', '.') : '—' }}
                                    </dd>
                                </div>
                                <div class="flex items-start justify-between gap-4">
                                    <dt class="text-sm font-medium text-gray-600 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Ketua Penelitian
                                    </dt>
                                    <dd class="text-sm font-bold text-gray-900 text-right">{{ $penelitian->ketua->nama ?? '—' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </article>

                    {{-- Team Card --}}
                    <article class="rounded-2xl bg-white shadow-lg ring-1 ring-gray-200 overflow-hidden transition-all hover:shadow-xl">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <h2 class="text-lg font-bold text-white">Tim Penelitian</h2>
                                </div>
                                <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-semibold text-white backdrop-blur-sm">
                                    {{ 1 + $anggota->count() + $pendukung->count() }} orang
                                </span>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            {{-- Ketua --}}
                            <div>
                                <p class="text-xs uppercase tracking-wide text-emerald-600 font-semibold mb-2">Ketua</p>
                                <div class="flex items-center gap-3 p-4 bg-emerald-50 border-2 border-emerald-200 rounded-xl">
                                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($penelitian->ketua->nama ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ $penelitian->ketua->nama ?? '—' }}</p>
                                        <p class="text-xs text-gray-600 truncate">{{ $penelitian->ketua->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Anggota --}}
                            @if($anggota->count())
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-2">Anggota ({{ $anggota->count() }})</p>
                                    <div class="space-y-2">
                                        @foreach($anggota as $member)
                                            <div class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors">
                                                <div class="flex-shrink-0 w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                    {{ strtoupper(substr($member->nama, 0, 1)) }}
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $member->nama }}</p>
                                                    <p class="text-xs text-gray-600 truncate">{{ $member->email }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Mahasiswa --}}
                            @if($pendukung->count())
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-2">Mahasiswa ({{ $pendukung->count() }})</p>
                                    <div class="space-y-2">
                                        @foreach($pendukung as $m)
                                            <div class="flex items-center gap-3 p-3 bg-blue-50 border border-blue-200 rounded-xl">
                                                <div class="flex-shrink-0 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                    {{ strtoupper(substr($m->nama, 0, 1)) }}
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $m->nama }}</p>
                                                    <p class="text-xs text-gray-600 truncate">{{ $m->email }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if(!$anggota->count() && !$pendukung->count())
                                <div class="text-center py-6">
                                    <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 rounded-full mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-500">Belum ada anggota tim</p>
                                </div>
                            @endif
                        </div>
                    </article>
                </div>

                {{-- Row 2: Documentation (Law of Common Region) --}}
                <article class="rounded-2xl bg-white shadow-lg ring-1 ring-gray-200 overflow-hidden transition-all hover:shadow-xl">
                    <div class="bg-gradient-to-r from-amber-600 to-amber-700 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h2 class="text-lg font-bold text-white">Dokumentasi</h2>
                            </div>
                            <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-semibold text-white backdrop-blur-sm">
                                {{ $penelitian->dokumentasi->count() }} berkas
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($penelitian->dokumentasi->count())
                            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach($penelitian->dokumentasi as $dok)
                                    <div class="group relative overflow-hidden rounded-xl border-2 border-gray-200 bg-white hover:border-amber-300 hover:shadow-lg transition-all">
                                        <div class="relative aspect-video overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
                                            <img src="{{ asset('storage/'.$dok->gdrive_path) }}" 
                                                 alt="{{ $dok->file_name }}" 
                                                 class="h-full w-full object-cover transition duration-300 group-hover:scale-110">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                        </div>
                                        <div class="p-4">
                                            <p class="font-semibold text-gray-900 truncate text-sm mb-1" title="{{ $dok->file_name }}">
                                                {{ $dok->file_name }}
                                            </p>
                                            <div class="flex items-center justify-between text-xs text-gray-500">
                                                <span>{{ number_format(($dok->size ?? 0) / 1024, 0) }} KB</span>
                                                <span class="px-2 py-0.5 bg-gray-100 rounded text-xs">{{ strtoupper(pathinfo($dok->file_name, PATHINFO_EXTENSION)) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-16">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-amber-100 rounded-full mb-4">
                                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">Belum Ada Dokumentasi</h3>
                                <p class="text-sm text-gray-600">Dokumentasi penelitian belum diunggah</p>
                            </div>
                        @endif
                    </div>
                </article>
            </div>

            {{-- Footer Spacing --}}
            <div class="h-8"></div>
        </div>
    </div>
</x-app-layout>
