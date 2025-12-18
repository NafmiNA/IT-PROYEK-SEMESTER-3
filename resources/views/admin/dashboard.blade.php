{{-- Dashboard with Sidebar Integration --}}
<x-app-layout>
  <style>
    /* Custom animations - Doherty Threshold <400ms */
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes slideIn { from { opacity: 0; transform: translateX(-20px); } to { opacity: 1; transform: translateX(0); } }
    .animate-fade-in { animation: fadeIn 0.3s ease-out; }
    .animate-slide-in { animation: slideIn 0.3s ease-out; }
    .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-lift:hover { transform: translateY(-2px); box-shadow: 0 12px 24px -10px rgba(0,0,0,0.15); }
    .focus-ring:focus-visible { outline: 3px solid #6a1fccff; outline-offset: 2px; border-radius: 0.5rem; }
    /* Hide scrollbar */
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .hide-scrollbar::-webkit-scrollbar { display: none; }
  </style>

  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

      {{-- 1) Hero Banner --}}
      <section class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl shadow-xl p-6 sm:p-8 text-white mb-6 sm:mb-8 animate-fade-in">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
          {{-- Profile --}}
          <div class="flex items-center gap-4">
            <div class="h-14 w-14 sm:h-16 sm:w-16 rounded-full bg-white/20 backdrop-blur-sm border-2 border-white/30 
                        flex items-center justify-center font-bold text-2xl shadow-lg">
              {{-- 
                MODIFIKASI: Variabel $dosen ini dikirim dari AdminDashboardController, 
                isinya adalah data User Admin yang sedang login 
              --}}
              {{ strtoupper(substr($dosen->name ?? 'A',0,1)) }}
            </div>
            <div>
              <p class="text-sm text-blue-100">Selamat datang kembali, (Admin)</p>
              {{-- MODIFIKASI: Menggunakan 'name' dari model User --}}
              <h2 class="font-bold text-xl sm:text-2xl">{{ $dosen->name ?? 'Admin P3M' }}</h2>
              <p class="text-sm text-blue-100">{{ $dosen->email ?? 'admin@kampus.ac.id' }}</p>
            </div>
          </div>

          {{-- Primary CTAs --}}
          
          <!-- PERBAIKAN: Menghapus komentar Blade {{-- --}} yang salah dan hanya menggunakan HTML comment -->
          <!--
          <div class="flex flex-wrap gap-3">
            {{-- MODIFIKASI: Rute diubah ke 'admin.penelitian.create' --}}
            <a href="{{ route('admin.penelitian.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl px-6 py-3 text-sm font-semibold
                      bg-white text-blue-700 hover:bg-blue-50 transition-all duration-200
                      shadow-lg hover:shadow-xl transform hover:scale-105 focus-ring">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              Tambah Penelitian
            </a>
            {{-- MODIFIKASI: Rute diubah ke 'admin.pengabdian.create' --}}
            <a href="{{ route('admin.pengabdian.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl px-6 py-3 text-sm font-semibold
                      bg-emerald-500 text-white hover:bg-emerald-600 transition-all duration-200
                      shadow-lg hover:shadow-xl transform hover:scale-105 focus-ring">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              Tambah Pengabdian
            </a>
          </div>
          -->
          
        </div>
      </section>

      {{-- 
        ==============================================================
        == TAMBAHAN BARU: FITUR KHUSUS ADMIN (KELOLA AKUN) ==
        ==============================================================
      --}}
      {{-- PERUBAHAN: 'bg-rose-100 border-rose-300' diubah menjadi 'bg-blue-50 border-blue-200' --}}
      <section class="bg-blue-50 border-2 border-blue-200 rounded-2xl shadow-lg p-6 sm:p-8 mb-6 sm:mb-8 animate-fade-in">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            
            {{-- Deskripsi Fitur --}}
            <div class="flex items-center gap-4">
                {{-- PERUBAHAN: 'bg-rose-200 border-rose-300' diubah menjadi 'bg-blue-100 border-blue-200' --}}
                <div class="h-14 w-14 sm:h-16 sm:w-16 rounded-full bg-blue-100 border-2 border-blue-200 
                                flex items-center justify-center shadow-lg">
                    {{-- PERUBAHAN: 'text-rose-700' diubah menjadi 'text-blue-700' --}}
                    <svg class="w-8 h-8 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    {{-- PERUBAHAN: 'text-rose-900' diubah menjadi 'text-blue-900' --}}
                    <h2 class="font-bold text-xl sm:text-2xl text-blue-900">Kelola Akun Pengguna</h2>
                    {{-- PERUBAHAN: 'text-rose-700' diubah menjadi 'text-blue-700' --}}
                    <p class="text-sm text-blue-700">Fitur khusus Admin Koordinator P3M untuk mengelola semua akun.</p>
                </div>
            </div>
      
            {{-- Tombol Aksi (CTA) --}}
            <div class="flex flex-wrap gap-3">
                {{-- Tombol ini mengarah ke 'users.index' (halaman tabel) --}}
                <a href="{{ route('admin.users.index') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl px-6 py-3 text-sm font-semibold
                          bg-white text-blue-700 border-2 border-blue-300 hover:bg-blue-700 hover:text-white hover:border-blue-700 
                          transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 focus-ring">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Buka Halaman Kelola Akun
                </a>
            </div>
        </div>
      </section>
      {{-- =============================================================== --}}

      {{-- 
        ==============================================================
        == TAMBAHAN BARU: CLOUD STORAGE MANAGEMENT ==
        ==============================================================
      --}}
      <section class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl shadow-lg p-6 sm:p-8 mb-6 sm:mb-8 animate-fade-in">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            
            {{-- Deskripsi Fitur --}}
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 sm:h-16 sm:w-16 rounded-full bg-gradient-to-br from-green-100 to-emerald-100 border-2 border-green-200 
                                flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl sm:text-2xl text-green-900">Penyimpanan Cloud</h2>
                    <p class="text-sm text-green-700">Kelola backup file ke Google Drive secara otomatis</p>
                </div>
            </div>
      
            {{-- Tombol Aksi (CTA) --}}
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.cloud-storage.settings') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl px-6 py-3 text-sm font-semibold
                          bg-white text-green-700 border-2 border-green-300 hover:bg-green-700 hover:text-white hover:border-green-700
                          transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 focus-ring">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Pengaturan Cloud Storage
                </a>
            </div>
        </div>
      </section>
      {{-- =============================================================== --}}


      {{-- 2) KPI Cards --}}
      @php
        $kpi = $kpi ?? ['penelitian'=>0,'pengabdian'=>0,'dokumentasi'=>0,'pending'=>0];
      @endphp
      <section class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
        {{-- (Data KPI ini global, diambil dari AdminDashboardController) --}}
        {{-- Penelitian Card --}}
        <div class="bg-white rounded-xl shadow-md hover-lift p-5 sm:p-6 border-l-4 border-gray-300 focus-ring animate-slide-in" tabindex="0" style="animation-delay: 0.1s">
          <div class="flex items-center justify-between mb-3">
            <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center">
              <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
            </div>
          </div>
          <p class="text-3xl sm:text-4xl font-bold text-gray-900">{{ $kpi['penelitian'] }}</p>
          <p class="text-sm text-gray-600 mt-1">Total Penelitian</p>
          <p class="text-xs text-gray-400 mt-1">Semua Dosen</p>
        </div>

        {{-- Pengabdian Card --}}
        <div class="bg-white rounded-xl shadow-md hover-lift p-5 sm:p-6 border-l-4 border-gray-300 focus-ring animate-slide-in" tabindex="0" style="animation-delay: 0.2s">
          <div class="flex items-center justify-between mb-3">
            <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center">
              <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
              </svg>
            </div>
          </div>
          <p class="text-3xl sm:text-4xl font-bold text-gray-900">{{ $kpi['pengabdian'] }}</p>
          <p class="text-sm text-gray-600 mt-1">Total Pengabdian</p>
          <p class="text-xs text-gray-400 mt-1">Semua Dosen</p>
        </div>

        {{-- Dokumentasi Card --}}
        <div class="bg-white rounded-xl shadow-md hover-lift p-5 sm:p-6 border-l-4 border-gray-300 focus-ring animate-slide-in" tabindex="0" style="animation-delay: 0.3s">
          <div class="flex items-center justify-between mb-3">
            <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center">
              <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
              </svg>
            </div>
          </div>
          <p class="text-3xl sm:text-4xl font-bold text-gray-900">{{ $kpi['dokumentasi'] }}</p>
          <p class="text-sm text-gray-600 mt-1">Total Dokumen</p>
          <p class="text-xs text-gray-400 mt-1">Semua File</p>
        </div>

        {{-- Pending Card --}}
        <div class="bg-white rounded-xl shadow-md hover-lift p-5 sm:p-6 border-l-4 border-gray-300 focus-ring animate-slide-in" tabindex="0" style="animation-delay: 0.4s">
          <div class="flex items-center justify-between mb-3">
            <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center">
              <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            @if($kpi['pending'] > 0)
              <span class="px-2 py-1 bg-gray-200 text-gray-700 text-xs font-semibold rounded-full animate-pulse">Perlu Aksi</span>
            @endif
          </div>
          <p class="text-3xl sm:text-4xl font-bold text-gray-900">{{ $kpi['pending'] }}</p>
          <p class="text-sm text-gray-600 mt-1">Menunggu Verifikasi</p>
          <p class="text-xs text-gray-400 mt-1">Item tertunda</p>
        </div>
      </section>

      {{-- 3) Chart & Summary --}}
      @php
        $yearSummary = $yearSummary ?? ['penelitian'=>0,'pengabdian'=>0,'approved'=>0,'rejected'=>0];
        $currentYear = date('Y');
        $totalYear = $yearSummary['penelitian'] + $yearSummary['pengabdian'];
      @endphp
      <section class="bg-white rounded-2xl shadow-md border border-gray-200 p-6 sm:p-8 mb-6 sm:mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {{-- Chart --}}
          <div class="lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
              <div>
                <h3 class="text-lg font-semibold text-gray-900">Tren 5 Tahun Terakhir (Semua Dosen)</h3>
                <p class="text-sm text-gray-500">Pertumbuhan penelitian dan pengabdian</p>
              </div>
              <div class="flex gap-4 text-xs">
                <div class="flex items-center gap-2">
                  <div class="w-3 h-3 rounded-full bg-blue-600"></div>
                  <span class="text-gray-600">Penelitian</span>
                </div>
                <div class="flex items-center gap-2">
                  <div class="w-3 h-3 rounded-full bg-emerald-600"></div>
                  <span class="text-gray-600">Pengabdian</span>
                </div>
              </div>
            </div>
            <div class="h-[280px] bg-gray-50 rounded-xl p-4">
              <canvas id="trendChart" class="!h-full !w-full"></canvas>
            </div>
          </div>

          {{-- Year Summary --}}
          <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-semibold text-gray-900">Tahun {{ $currentYear }} (Global)</h3>
              <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
              </div>
            </div>
            <div class="space-y-4">
              {{-- Penelitian Progress --}}
              <div>
                <div class="flex items-center justify-between mb-2">
                  <span class="text-sm text-gray-600">Penelitian</span>
                  <span class="text-lg font-bold text-gray-900">{{ $yearSummary['penelitian'] }}</span>
                </div>
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                  <div class="h-full bg-blue-600 rounded-full transition-all duration-500" 
                       style="width: {{ $totalYear > 0 ? ($yearSummary['penelitian'] / $totalYear * 100) : 0 }}%"></div>
                </div>
              </div>

              {{-- Pengabdian Progress --}}
              <div>
                <div class="flex items-center justify-between mb-2">
                  <span class="text-sm text-gray-600">Pengabdian</span>
                  <span class="text-lg font-bold text-gray-900">{{ $yearSummary['pengabdian'] }}</span>
                </div>
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                  <div class="h-full bg-emerald-600 rounded-full transition-all duration-500" 
                       style="width: {{ $totalYear > 0 ? ($yearSummary['pengabdian'] / $totalYear * 100) : 0 }}%"></div>
                </div>
              </div>

              {{-- Status Summary --}}
              <div class="pt-4 border-t border-gray-200">
                <div class="flex items-center justify-between mb-2">
                  <span class="text-sm text-gray-600">Disetujui (Semua Tahun)</span>
                  <span class="text-lg font-bold text-emerald-600">{{ $yearSummary['approved'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-sm text-gray-600">Ditolak (Semua Tahun)</span>
                  <span class="text-lg font-bold text-rose-600">{{ $yearSummary['rejected'] }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {{-- 4) Recent Activity --}}
      <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Penelitian Terbaru --}}
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
          <div class="flex justify-between items-center mb-5">
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Penelitian Terbaru (Global)</h3>
              <p class="text-xs text-gray-500 mt-1">Aktivitas terakhir semua dosen</p>
            </div>
            {{-- MODIFIKASI: Rute diubah ke 'admin.penelitian.index' --}}
            <a href="{{ route('admin.penelitian.index') }}" 
               class="text-sm text-blue-600 hover:text-blue-700 font-medium transition focus-ring rounded px-3 py-1.5">
              Lihat semua →
            </a>
          </div>
          <div class="space-y-3">
            @forelse(($latestPenelitian ?? []) as $item)
              <article class="p-4 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer border border-gray-100 hover:border-blue-200">
                <div class="flex justify-between items-start gap-3">
                  <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-900 truncate mb-2">{{ $item->judul }}</p>
                    <div class="flex flex-wrap gap-2">
                      <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded">
                        {{ $item->skema ?? 'Umum' }}
                      </span>
                      <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">
                        {{ $item->tahun }}
                      </span>
                    </div>
                  </div>
                  <span class="text-xs text-gray-400 whitespace-nowrap">{{ optional($item->created_at)->diffForHumans() }}</span>
                </div>
              </article>
            @empty
              {{-- Empty State --}}
              <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                  <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                </div>
                <p class="text-sm text-gray-600 mb-3">Belum ada penelitian</p>
                {{-- MODIFIKASI: Rute diubah ke 'admin.penelitian.create' --}}
                <a href="{{ route('admin.penelitian.create') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                  Buat penelitian pertama →
                </a>
              </div>
            @endforelse
          </div>
        </div>

        {{-- Pengabdian Terbaru --}}
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
          <div class="flex justify-between items-center mb-5">
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Pengabdian Terbaru (Global)</h3>
              <p class="text-xs text-gray-500 mt-1">Aktivitas terakhir semua dosen</p>
            </div>
            {{-- MODIFIKASI: Rute diubah ke 'admin.pengabdian.index' --}}
            <a href="{{ route('admin.pengabdian.index') }}" 
               class="text-sm text-emerald-600 hover:text-emerald-700 font-medium transition focus-ring rounded px-3 py-1.5">
              Lihat semua →
            </a>
          </div>
          <div class="space-y-3">
            @forelse(($latestPengabdian ?? []) as $item)
              <article class="p-4 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer border border-gray-100 hover:border-emerald-200">
                <div class="flex justify-between items-start gap-3">
                  <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-900 truncate mb-2">{{ $item->judul }}</p>
                    <div class="flex flex-wrap gap-2">
                      <span class="inline-flex items-center px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-medium rounded">
                        {{ $item->bidang ?? 'Umum' }}
                      </span>
                      <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">
                        {{ $item->tahun }}
                      </span>
                    </div>
                  </div>
                  <span class="text-xs text-gray-400 whitespace-nowrap">{{ optional($item->created_at)->diffForHumans() }}</span>
                </div>
              </article>
            @empty
              {{-- Empty State --}}
              <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                  <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                  </svg>
                </div>
                <p class="text-sm text-gray-600 mb-3">Belum ada pengabdian</p>
                {{-- MODIFIKASI: Rute diubah ke 'admin.pengabdian.create' --}}
                <a href="{{ route('admin.pengabdian.create') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                  Buat pengabdian pertama →
                </a>
              </div>
            @endforelse
          </div>
        </div>
      </section>

      {{-- Footer spacing --}}
      <div class="h-12"></div>

    </main>
  </div>

  {{-- Chart.js --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const trend = @json($trend ?? []);

    const ctx = document.getElementById('trendChart');
    if (ctx && trend.length) {
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: trend.map(t => t.tahun),
          datasets: [
            {
              label: 'Penelitian',
              data: trend.map(t => t.penelitian),
              borderColor: '#2563eb',
              backgroundColor: 'rgba(37,99,235,0.1)',
              borderWidth: 3,
              tension: 0.4,
              fill: true,
              pointRadius: 4,
              pointBackgroundColor: '#2563eb',
              pointBorderColor: '#fff',
              pointBorderWidth: 2,
              pointHoverRadius: 6
            },
            {
              label: 'Pengabdian',
              data: trend.map(t => t.pengabdian),
              borderColor: '#059669',
              backgroundColor: 'rgba(5,150,105,0.1)',
              borderWidth: 3,
              tension: 0.4,
              fill: true,
              pointRadius: 4,
              pointBackgroundColor: '#059669',
              pointBorderColor: '#fff',
              pointBorderWidth: 2,
              pointHoverRadius: 6
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          interaction: {
            mode: 'index',
            intersect: false
          },
          scales: {
            x: { 
              grid: { display: false },
              ticks: { font: { size: 12 }, color: '#6b7280' }
            },
            y: { 
              beginAtZero: true, 
              ticks: { precision: 0, font: { size: 12 }, color: '#6b7280' },
              grid: { color: '#f3f4f6' }
            }
          },
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              backgroundColor: '#1f2937',
              titleColor: '#fff',
              bodyColor: '#fff',
              padding: 12,
              cornerRadius: 8,
              displayColors: true
            }
          }
        }
      });
    }
  </script>

</x-app-layout>