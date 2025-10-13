@extends('layouts.mahasiswa')

@section('content')
{{-- Modern UX-focused Dashboard for Mahasiswa (15 Laws Applied) --}}
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50/30 to-gray-50">
    <style>
      .btn-mini{display:inline-flex;align-items:center;gap:.35rem;height:30px;padding:0 10px;border-radius:8px;font-weight:600;font-size:.75rem;white-space:nowrap}
      .btn-detail{background:#2563eb;color:#fff}
      .btn-detail:hover{background:#1d4ed8}
      .btn-edit{background:#f97316;color:#fff}
      .btn-edit:hover{background:#ea580c}
      .btn-danger{background:#e11d48;color:#fff}
      .btn-danger:hover{background:#be123c}
    </style>
    <div class="mx-auto max-w-7xl px-4 py-8">
        
        {{-- Header with User Info (Serial Position Effect) --}}
        <div class="mb-8 animate-slide-up">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wider text-blue-600/70 font-semibold mb-1">Dashboard Mahasiswa</p>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">
                        Selamat Datang, {{ $profilMahasiswa->nama ?? Auth::user()->name ?? 'Mahasiswa' }}! 👋
                    </h1>
                    <p class="text-gray-600">
                        <span class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ $profilMahasiswa->email ?? Auth::user()->email }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Success Message (Feedback) --}}
        @if (session('success'))
            <div class="mb-6 animate-slide-up">
                <div class="flex items-start gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-lg shadow-sm">
                    <div class="flex-shrink-0 w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-emerald-700">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.closest('[class*=animate-slide-up]').remove()" 
                            class="flex-shrink-0 text-emerald-500 hover:text-emerald-700 transition-colors"
                            aria-label="Tutup">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        {{-- Stats Cards (Law of Common Region + Visual Hierarchy) --}}
        <div class="grid gap-6 md:grid-cols-3 mb-8 animate-fade">
            {{-- Penelitian Card --}}
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 to-blue-700 p-6 shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Penelitian Saya</p>
                    <p class="text-4xl font-bold text-white mb-1">{{ $penelitianList->count() }}</p>
                    <p class="text-blue-200 text-xs">Program penelitian aktif</p>
                </div>
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
            </div>

            {{-- Pengabdian Card --}}
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-600 to-emerald-700 p-6 shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-emerald-100 text-sm font-medium mb-1">Pengabdian Saya</p>
                    <p class="text-4xl font-bold text-white mb-1">{{ $pengabdianList->count() }}</p>
                    <p class="text-emerald-200 text-xs">Program pengabdian aktif</p>
                </div>
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
            </div>

            {{-- Dokumentasi Card --}}
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-600 to-amber-700 p-6 shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-amber-100 text-sm font-medium mb-1">Total Dokumentasi</p>
                    <p class="text-4xl font-bold text-white mb-1">{{ $dokumentasi->count() }}</p>
                    <p class="text-amber-200 text-xs">File yang diunggah</p>
                </div>
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
            </div>
        </div>

        {{-- Main Content (Law of Common Region) --}}
        <div class="grid gap-6 lg:grid-cols-2">
            
            {{-- Penelitian Section --}}
            <section class="rounded-2xl bg-white shadow-lg ring-1 ring-gray-200 overflow-hidden transition-all hover:shadow-xl animate-fade">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-white">Penelitian Saya</h2>
                                <p class="text-sm text-blue-100">Program yang saya ikuti</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-semibold text-white backdrop-blur-sm">
                            {{ $penelitianList->count() }}
                        </span>
                    </div>
                </div>
                
                <div class="p-6">
                    @if($penelitianList->isEmpty())
                        {{-- Empty State --}}
                        <div class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Belum Ada Penelitian</h3>
                            <p class="text-sm text-gray-600">Anda belum terdaftar dalam program penelitian apapun</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($penelitianList as $item)
                                @php
                                    $statusConfig = [
                                        'Disetujui' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200'],
                                        'Menunggu' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'border' => 'border-amber-200'],
                                        'Ditolak' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-200'],
                                        'Draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-200'],
                                    ];
                                    $status = $item->status ?? 'Draft';
                                    $config = $statusConfig[$status] ?? $statusConfig['Draft'];
                                @endphp
                                
                                <div class="group p-4 border-2 {{ $config['border'] }} rounded-xl hover:shadow-md transition-all">
                                    <div class="flex items-start gap-3 mb-3">
                                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 font-bold text-sm">
                                            {{ strtoupper(substr($item->judul, 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2">{{ $item->judul }}</h3>
                                            <div class="flex items-center gap-2 text-xs text-gray-600">
                                                <span class="inline-flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    {{ $item->ketua->nama ?? '—' }}
                                                </span>
                                                <span class="text-gray-300">•</span>
                                                <span>{{ $item->tahun }}</span>
                                            </div>
                                        </div>
                                        <span class="flex-shrink-0 px-2.5 py-1 {{ $config['bg'] }} {{ $config['text'] }} text-xs font-semibold rounded-full">
                                            {{ $status }}
                                        </span>
                                    </div>
                                    
                                    {{-- Upload Form --}}
                                    <form action="{{ route('mahasiswa.dokumentasi.store') }}" method="POST" enctype="multipart/form-data" class="mt-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        @csrf
                                        <input type="hidden" name="context" value="penelitian">
                                        <input type="hidden" name="context_id" value="{{ $item->id }}">
                                        <div class="flex flex-col sm:flex-row gap-2">
                                            <input type="file" 
                                                   name="dokumentasi[]" 
                                                   multiple 
                                                   required
                                                   class="flex-1 text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 file:cursor-pointer border border-gray-300 rounded-lg cursor-pointer">
                                            <button type="submit" 
                                                    class="flex-shrink-0 inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all whitespace-nowrap">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                </svg>
                                                Upload
                                            </button>
                                        </div>
                                    </form>

                                    {{-- List Dokumentasi --}}
                                    @if($item->dokumentasi->isNotEmpty())
                                        <div class="mt-3">
                                            <p class="text-sm font-semibold text-gray-700 mb-2">Dokumentasi Terunggah</p>
                                            <ul class="space-y-2">
                                                @foreach($item->dokumentasi as $doc)
                                                    <li class="flex items-center justify-between gap-3 p-2 border rounded-lg">
                                                        <span class="text-sm text-gray-800 truncate">{{ $doc->file_name ?? basename($doc->gdrive_path) }}</span>
                                                        <div class="flex items-center gap-2">
                                                            <a href="{{ asset('storage/'.$doc->gdrive_path) }}" target="_blank" class="btn-mini btn-detail">Detail</a>
                                                            <a href="{{ route('mahasiswa.dokumentasi.edit', $doc->dokumentasi_id) }}" class="btn-mini btn-edit">Edit</a>
                                                            <form action="{{ route('mahasiswa.dokumentasi.destroy', $doc->dokumentasi_id) }}" method="POST" onsubmit="return confirm('Hapus file ini?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn-mini btn-danger">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>

            {{-- Pengabdian Section --}}
            <section class="rounded-2xl bg-white shadow-lg ring-1 ring-gray-200 overflow-hidden transition-all hover:shadow-xl animate-fade">
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-white">Pengabdian Saya</h2>
                                <p class="text-sm text-emerald-100">Program yang saya ikuti</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-semibold text-white backdrop-blur-sm">
                            {{ $pengabdianList->count() }}
                        </span>
                    </div>
                </div>
                
                <div class="p-6">
                    @if($pengabdianList->isEmpty())
                        {{-- Empty State --}}
                        <div class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-emerald-100 rounded-full mb-4">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Belum Ada Pengabdian</h3>
                            <p class="text-sm text-gray-600">Anda belum terdaftar dalam program pengabdian apapun</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($pengabdianList as $item)
                                @php
                                    $statusConfig = [
                                        'Disetujui' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200'],
                                        'Menunggu' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'border' => 'border-amber-200'],
                                        'Ditolak' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-200'],
                                        'Draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-200'],
                                    ];
                                    $status = $item->status ?? 'Draft';
                                    $config = $statusConfig[$status] ?? $statusConfig['Draft'];
                                @endphp
                                
                                <div class="group p-4 border-2 {{ $config['border'] }} rounded-xl hover:shadow-md transition-all">
                                    <div class="flex items-start gap-3 mb-3">
                                        <div class="flex-shrink-0 w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 font-bold text-sm">
                                            {{ strtoupper(substr($item->judul, 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2">{{ $item->judul }}</h3>
                                            <div class="flex items-center gap-2 text-xs text-gray-600">
                                                <span class="inline-flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    {{ $item->ketua->nama ?? '—' }}
                                                </span>
                                                <span class="text-gray-300">•</span>
                                                <span>{{ $item->tahun }}</span>
                                            </div>
                                        </div>
                                        <span class="flex-shrink-0 px-2.5 py-1 {{ $config['bg'] }} {{ $config['text'] }} text-xs font-semibold rounded-full">
                                            {{ $status }}
                                        </span>
                                    </div>
                                    
                                    {{-- Upload Form --}}
                                    <form action="{{ route('mahasiswa.dokumentasi.store') }}" method="POST" enctype="multipart/form-data" class="mt-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        @csrf
                                        <input type="hidden" name="context" value="pengabdian">
                                        <input type="hidden" name="context_id" value="{{ $item->id }}">
                                        <div class="flex flex-col sm:flex-row gap-2">
                                            <input type="file" 
                                                   name="dokumentasi[]" 
                                                   multiple 
                                                   required
                                                   class="flex-1 text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 file:cursor-pointer border border-gray-300 rounded-lg cursor-pointer">
                                            <button type="submit" 
                                                    class="flex-shrink-0 inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all whitespace-nowrap">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                </svg>
                                                Upload
                                            </button>
                                        </div>
                                    </form>

                                    {{-- List Dokumentasi --}}
                                    @if($item->dokumentasi->isNotEmpty())
                                        <div class="mt-3">
                                            <p class="text-sm font-semibold text-gray-700 mb-2">Dokumentasi Terunggah</p>
                                            <ul class="space-y-2">
                                                @foreach($item->dokumentasi as $doc)
                                                    <li class="flex items-center justify-between gap-3 p-2 border rounded-lg">
                                                        <span class="text-sm text-gray-800 truncate">{{ $doc->file_name ?? basename($doc->gdrive_path) }}</span>
                                                        <div class="flex items-center gap-2">
                                                            <a href="{{ asset('storage/'.$doc->gdrive_path) }}" target="_blank" class="btn-mini btn-detail">Detail</a>
                                                            <a href="{{ route('mahasiswa.dokumentasi.edit', $doc->dokumentasi_id) }}" class="btn-mini btn-edit">Edit</a>
                                                            <form action="{{ route('mahasiswa.dokumentasi.destroy', $doc->dokumentasi_id) }}" method="POST" onsubmit="return confirm('Hapus file ini?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn-mini btn-danger">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>
        </div>

        {{-- Footer Spacing --}}
        <div class="h-8"></div>
    </div>
</div>
@endsection
