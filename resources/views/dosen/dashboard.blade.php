{{-- resources/views/dosen/dashboard.blade.php --}}
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
  </style>

  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

      {{-- 1) Hero Banner - Von Restorff Effect (distinctive), Fitts's Law (large CTAs) --}}
      <section class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl shadow-xl p-6 sm:p-8 text-white mb-6 sm:mb-8 animate-fade-in">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
          {{-- Profile - Law of Proximity (grouped info) --}}
          <div class="flex items-center gap-4">
            <div class="h-14 w-14 sm:h-16 sm:w-16 rounded-full bg-white/20 backdrop-blur-sm border-2 border-white/30 
                        flex items-center justify-center font-bold text-2xl shadow-lg">
              {{ strtoupper(substr($dosen->nama ?? 'A',0,1)) }}
            </div>
            <div>
              <p class="text-sm text-blue-100">Selamat datang kembali,</p>
              <h2 class="font-bold text-xl sm:text-2xl">{{ $dosen->nama ?? 'Andi Dosen' }}</h2>
              <p class="text-sm text-blue-100">{{ $dosen->email ?? 'andi@kampus.ac.id' }}</p>
            </div>
          </div>

          {{-- Primary CTAs - Fitts's Law (large targets, easy to click) --}}
          <div class="flex flex-wrap gap-3">
            <a href="{{ route('dosen.penelitian.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl px-6 py-3 text-sm font-semibold
                      bg-white text-blue-700 hover:bg-blue-50 transition-all duration-200
                      shadow-lg hover:shadow-xl transform hover:scale-105 focus-ring">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              Tambah Penelitian
            </a>
            <a href="{{ route('dosen.pengabdian.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl px-6 py-3 text-sm font-semibold
                      bg-emerald-500 text-white hover:bg-emerald-600 transition-all duration-200
                      shadow-lg hover:shadow-xl transform hover:scale-105 focus-ring">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              Tambah Pengabdian
            </a>
          </div>
        </div>
      </section>

      {{-- 2) KPI Cards - Miller's Law (4 items), Law of Similarity (consistent design) --}}
      @php
        $kpi = $kpi ?? ['penelitian'=>0,'pengabdian'=>0,'dokumentasi'=>0,'pending'=>0];
      @endphp
      <section class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
        {{-- Penelitian Card --}}
        <div class="bg-white rounded-xl shadow-md hover-lift p-5 sm:p-6 border-l-4 border-blue-600 focus-ring animate-slide-in" tabindex="0" style="animation-delay: 0.1s">
          <div class="flex items-center justify-between mb-3">
            <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
            </div>
          </div>
          <p class="text-3xl sm:text-4xl font-bold text-gray-900">{{ $kpi['penelitian'] }}</p>
          <p class="text-sm text-gray-600 mt-1">Total Penelitian</p>
          <p class="text-xs text-gray-400 mt-1">Semua waktu</p>
        </div>

        {{-- Pengabdian Card --}}
        <div class="bg-white rounded-xl shadow-md hover-lift p-5 sm:p-6 border-l-4 border-emerald-600 focus-ring animate-slide-in" tabindex="0" style="animation-delay: 0.2s">
          <div class="flex items-center justify-between mb-3">
            <div class="h-10 w-10 rounded-lg bg-emerald-100 flex items-center justify-center">
              <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
              </svg>
            </div>
          </div>
          <p class="text-3xl sm:text-4xl font-bold text-gray-900">{{ $kpi['pengabdian'] }}</p>
          <p class="text-sm text-gray-600 mt-1">Total Pengabdian</p>
          <p class="text-xs text-gray-400 mt-1">Semua waktu</p>
        </div>

        {{-- Dokumentasi Card --}}
        <div class="bg-white rounded-xl shadow-md hover-lift p-5 sm:p-6 border-l-4 border-purple-600 focus-ring animate-slide-in" tabindex="0" style="animation-delay: 0.3s">
          <div class="flex items-center justify-between mb-3">
            <div class="h-10 w-10 rounded-lg bg-purple-100 flex items-center justify-center">
              <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
              </svg>
            </div>
          </div>
          <p class="text-3xl sm:text-4xl font-bold text-gray-900">{{ $kpi['dokumentasi'] }}</p>
          <p class="text-sm text-gray-600 mt-1">Total Dokumen</p>
          <p class="text-xs text-gray-400 mt-1">File tersimpan</p>
        </div>

        {{-- Pending Card - Von Restorff Effect (highlight when action needed) --}}
        <div class="bg-white rounded-xl shadow-md hover-lift p-5 sm:p-6 border-l-4 {{ $kpi['pending'] > 0 ? 'border-amber-500' : 'border-gray-300' }} focus-ring animate-slide-in" tabindex="0" style="animation-delay: 0.4s">
          <div class="flex items-center justify-between mb-3">
            <div class="h-10 w-10 rounded-lg {{ $kpi['pending'] > 0 ? 'bg-amber-100' : 'bg-gray-100' }} flex items-center justify-center">
              <svg class="w-6 h-6 {{ $kpi['pending'] > 0 ? 'text-amber-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            @if($kpi['pending'] > 0)
              <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full animate-pulse">Perlu Aksi</span>
            @endif
          </div>
          <p class="text-3xl sm:text-4xl font-bold {{ $kpi['pending'] > 0 ? 'text-amber-600' : 'text-gray-900' }}">{{ $kpi['pending'] }}</p>
          <p class="text-sm text-gray-600 mt-1">Menunggu Verifikasi</p>
          <p class="text-xs text-gray-400 mt-1">Item tertunda</p>
        </div>
      </section>

      {{-- 3) Chart & Summary - Law of Common Region (clear boundaries), Goal-Gradient Effect (progress) --}}
      @php
        $yearSummary = $yearSummary ?? ['penelitian'=>0,'pengabdian'=>0,'approved'=>0,'rejected'=>0];
        $currentYear = date('Y');
        $totalYear = $yearSummary['penelitian'] + $yearSummary['pengabdian'];
      @endphp
      <section class="bg-white rounded-2xl shadow-md border border-gray-200 p-6 sm:p-8 mb-6 sm:mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {{-- Chart - Law of Prägnanz (simple, clear) --}}
          <div class="lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
              <div>
                <h3 class="text-lg font-semibold text-gray-900">Tren 5 Tahun Terakhir</h3>
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

          {{-- Year Summary - Goal-Gradient Effect (progress bars) --}}
          <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-semibold text-gray-900">Tahun {{ $currentYear }}</h3>
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
                  <span class="text-sm text-gray-600">Disetujui</span>
                  <span class="text-lg font-bold text-emerald-600">{{ $yearSummary['approved'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-sm text-gray-600">Ditolak</span>
                  <span class="text-lg font-bold text-rose-600">{{ $yearSummary['rejected'] }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {{-- 4) Recent Activity - Law of Proximity (grouped), Empty States with guidance --}}
      <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Penelitian Terbaru --}}
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
          <div class="flex justify-between items-center mb-5">
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Penelitian Terbaru</h3>
              <p class="text-xs text-gray-500 mt-1">Aktivitas terakhir</p>
            </div>
            <a href="{{ route('dosen.penelitian.index') }}" 
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
              {{-- Empty State with guidance - Provides clear next action --}}
              <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                  <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                </div>
                <p class="text-sm text-gray-600 mb-3">Belum ada penelitian</p>
                <a href="{{ route('dosen.penelitian.create') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
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
              <h3 class="text-lg font-semibold text-gray-900">Pengabdian Terbaru</h3>
              <p class="text-xs text-gray-500 mt-1">Aktivitas terakhir</p>
            </div>
            <a href="{{ route('dosen.pengabdian.index') }}" 
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
              {{-- Empty State with guidance --}}
              <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                  <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                  </svg>
                </div>
                <p class="text-sm text-gray-600 mb-3">Belum ada pengabdian</p>
                <a href="{{ route('dosen.pengabdian.create') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
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
