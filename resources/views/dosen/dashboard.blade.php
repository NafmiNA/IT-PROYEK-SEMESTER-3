{{-- resources/views/dosen/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Dosen</title>
  <!-- Tailwind CDN -->
  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>

<body class="min-h-screen bg-[#F5F7FA] text-gray-800">

  <!-- Topbar -->
  <header class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
      <div class="flex items-center gap-3">
        <img src="{{ asset('images/logo-full.png') }}" alt="Logo Politala" class="h-10 w-10 object-contain">
        <h1 class="text-lg font-semibold text-[#2050A0]">Dashboard Dosen</h1>
      </div>
      <span class="text-sm text-gray-400">{{ now()->format('d M Y') }}</span>
    </div>
  </header>

  <main class="max-w-7xl mx-auto px-6 py-8 space-y-8">

    <!-- 1) BOX: Profil + Aksi -->
    <section
      class="bg-white rounded-2xl border-2 border-gray-200 ring-1 ring-gray-300/60 shadow-md p-6">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <!-- Profil -->
        <div class="flex items-center gap-4">
          <div class="h-12 w-12 rounded-full bg-emerald-600 text-white grid place-content-center font-bold">
            {{ strtoupper(substr($dosen->nama ?? 'A',0,1)) }}
          </div>
          <div>
            <p class="text-sm text-gray-500">Selamat datang kembali,</p>
            <h2 class="font-semibold text-lg">{{ $dosen->nama ?? 'Andi Dosen' }}</h2>
            <p class="text-sm text-gray-500">{{ $dosen->email ?? 'andi@kampus.ac.id' }}</p>
          </div>
        </div>

        <!-- Tombol aksi -->
        <div class="flex flex-wrap gap-2">
          <a href="{{ route('dosen.penelitian.create') }}"
             class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium
                    bg-[#2050A0] text-white hover:bg-[#163B78] transition">
            + Penelitian
          </a>
          <a href="{{ route('dosen.pengabdian.create') }}"
             class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium
                    bg-emerald-600 text-white hover:bg-emerald-700 transition">
            + Pengabdian
          </a>
        </div>
      </div>
    </section>

    <!-- 2) BOX: KPI -->
    @php
      $kpi = $kpi ?? ['penelitian'=>0,'pengabdian'=>0,'dokumentasi'=>0,'pending'=>0];
    @endphp
    <section
      class="bg-white rounded-2xl border-2 border-gray-200 ring-1 ring-gray-300/60 shadow-md p-6">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        <!-- KPI card -->
        <div class="bg-white rounded-xl border-2 border-gray-200 ring-1 ring-gray-300/50 shadow-sm py-5 text-center">
          <h3 class="text-sm font-semibold text-[#2050A0] uppercase tracking-wide">Total Penelitian</h3>
          <p class="text-3xl font-bold text-[#2050A0] mt-2">{{ $kpi['penelitian'] }}</p>
          <span class="text-xs text-gray-500">semua waktu</span>
        </div>

        <div class="bg-white rounded-xl border-2 border-gray-200 ring-1 ring-gray-300/50 shadow-sm py-5 text-center">
          <h3 class="text-sm font-semibold text-[#2050A0] uppercase tracking-wide">Total Pengabdian</h3>
          <p class="text-3xl font-bold text-[#2050A0] mt-2">{{ $kpi['pengabdian'] }}</p>
          <span class="text-xs text-gray-500">semua waktu</span>
        </div>

        <div class="bg-white rounded-xl border-2 border-gray-200 ring-1 ring-gray-300/50 shadow-sm py-5 text-center">
          <h3 class="text-sm font-semibold text-[#2050A0] uppercase tracking-wide">Total Dokumentasi</h3>
          <p class="text-3xl font-bold text-[#2050A0] mt-2">{{ $kpi['dokumentasi'] }}</p>
          <span class="text-xs text-gray-500">berkas</span>
        </div>

        <div class="bg-white rounded-xl border-2 border-gray-200 ring-1 ring-gray-300/50 shadow-sm py-5 text-center">
          <h3 class="text-sm font-semibold text-[#2050A0] uppercase tracking-wide">Menunggu Verifikasi</h3>
          <p class="text-3xl font-bold text-[#2050A0] mt-2">{{ $kpi['pending'] }}</p>
          <span class="text-xs text-gray-500">item</span>
        </div>
      </div>
    </section>

    <!-- 3) BOX: Grafik & Ringkasan -->
    @php
      $yearSummary = $yearSummary ?? ['penelitian'=>0,'pengabdian'=>0,'approved'=>0,'rejected'=>0];
    @endphp
    <section
      class="bg-white rounded-2xl border-2 border-gray-200 ring-1 ring-gray-300/60 shadow-md p-6">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Grafik -->
        <div class="lg:col-span-2">
          <div class="flex justify-between items-center mb-3">
            <h3 class="font-semibold text-[#2050A0]">Tren 5 Tahun Terakhir</h3>
            <span class="text-xs text-gray-400">per Tahun</span>
          </div>
          <div class="h-[260px] bg-white rounded-xl border border-gray-200 p-3">
            <canvas id="trendChart" class="!h-full !w-full"></canvas>
          </div>
        </div>

        <!-- Ringkasan Tahun -->
        <div class="bg-white rounded-xl border-2 border-gray-200 ring-1 ring-gray-300/50 shadow-sm p-5">
          <h3 class="font-semibold text-[#2050A0] mb-3">Tahun {{ date('Y') }}</h3>
          <div class="space-y-3 text-sm">
            <div class="flex justify-between"><span class="text-gray-500">Penelitian</span><span class="font-semibold">{{ $yearSummary['penelitian'] }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Pengabdian</span><span class="font-semibold">{{ $yearSummary['pengabdian'] }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Disetujui</span><span class="font-semibold text-emerald-600">{{ $yearSummary['approved'] }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Ditolak</span><span class="font-semibold text-rose-600">{{ $yearSummary['rejected'] }}</span></div>
          </div>
        </div>
      </div>
    </section>

    <!-- 4) BOX: Daftar Terbaru -->
    <section
      class="bg-white rounded-2xl border-2 border-gray-200 ring-1 ring-gray-300/60 shadow-md p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Penelitian -->
      <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex justify-between items-center mb-3">
          <h3 class="font-semibold text-[#2050A0]">Penelitian Terbaru</h3>
          <a href="{{ route('dosen.penelitian.index') }}" class="text-xs text-gray-500 hover:text-[#2050A0]">Lihat semua</a>
        </div>
        <div class="divide-y divide-gray-200">
          @forelse(($latestPenelitian ?? []) as $item)
            <article class="py-3 flex justify-between text-sm">
              <div>
                <p class="font-medium">{{ $item->judul }}</p>
                <p class="text-xs text-gray-500">Skema: {{ $item->skema ?? '-' }} • Tahun: {{ $item->tahun }}</p>
              </div>
              <span class="text-xs text-gray-400 whitespace-nowrap">{{ optional($item->created_at)->diffForHumans() }}</span>
            </article>
          @empty
            <p class="text-gray-500 text-sm py-6 text-center">Tidak ada data</p>
          @endforelse
        </div>
      </div>

      <!-- Pengabdian -->
      <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex justify-between items-center mb-3">
          <h3 class="font-semibold text-[#2050A0]">Pengabdian Terbaru</h3>
          <a href="{{ route('dosen.pengabdian.index') }}" class="text-xs text-gray-500 hover:text-[#2050A0]">Lihat semua</a>
        </div>
        <div class="divide-y divide-gray-200">
          @forelse(($latestPengabdian ?? []) as $item)
            <article class="py-3 flex justify-between text-sm">
              <div>
                <p class="font-medium">{{ $item->judul }}</p>
                <p class="text-xs text-gray-500">Bidang: {{ $item->bidang ?? '-' }} • Tahun: {{ $item->tahun }}</p>
              </div>
              <span class="text-xs text-gray-400 whitespace-nowrap">{{ optional($item->created_at)->diffForHumans() }}</span>
            </article>
          @empty
            <p class="text-gray-500 text-sm py-6 text-center">Tidak ada data</p>
          @endforelse
        </div>
      </div>
    </section>

  </main>

  <!-- Chart.js -->
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
              borderColor: '#2050A0',
              backgroundColor: 'rgba(32,80,160,.14)',
              tension: .35, fill: true, pointRadius: 3
            },
            {
              label: 'Pengabdian',
              data: trend.map(t => t.pengabdian),
              borderColor: '#059669',
              backgroundColor: 'rgba(5,150,105,.12)',
              tension: .35, fill: true, pointRadius: 3
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            x: { grid: { display: false } },
            y: { beginAtZero: true, ticks: { precision: 0 } }
          },
          plugins: { legend: { labels: { usePointStyle: true, boxWidth: 10 } } }
        }
      });
    }
  </script>
</body>
</html>
