<x-app-layout>
  <x-slot name="header">
    <div class="topbar">
      <h2>Dashboard Dosen</h2>
      <span class="date">{{ now()->translatedFormat('d M Y') }}</span>
    </div>
  </x-slot>

  {{-- ==== CSS ==== --}}
  <style>
    :root{
      /* Ganti sesuai warna korporat */
      --primary:#2050A0; /* AM 205 – biru */
      --accent:#31A57F;  /* AM 311 – hijau */
      --bg:#f5f7fa;
      --card:#fff;
      --muted:#6b7280;
      --text:#111827;
      --ring:#e5e7eb;
    }
    body{background:var(--bg);}
    .wrap{padding:32px 0;background:var(--bg);}
    .container{max-width:1100px;margin:0 auto;padding:0 24px;}
    .topbar{display:flex;align-items:center;justify-content:space-between}
    .topbar h2{margin:0;font-size:20px;color:var(--primary)}
    .topbar .date{font-size:12px;color:var(--muted)}

    .card{background:var(--card);border:1px solid var(--ring);border-radius:14px;
          box-shadow:0 1px 3px rgba(0,0,0,.06)}
    .card.pad{padding:20px}

    .profile{display:flex;align-items:center;gap:14px}
    .avatar{height:54px;width:54px;border-radius:50%;display:flex;align-items:center;
            justify-content:center;background:var(--accent);color:#fff;font-weight:700;font-size:18px}
    .name{margin:0;font-weight:600;color:var(--text)}
    .email{margin:4px 0 0;color:var(--muted);font-size:13px}

    .grid4{display:grid;grid-template-columns:repeat(4,1fr);gap:18px}
    @media (max-width:980px){.grid4{grid-template-columns:repeat(2,1fr)}}
    @media (max-width:560px){.grid4{grid-template-columns:1fr}}

    .metric{padding:18px;text-align:center}
    .metric .label{font-size:12px;color:var(--muted)}
    .metric .value{margin-top:6px;font-size:28px;font-weight:700;color:var(--primary)}

    .section-title{margin:0 0 10px 0;font-weight:600;color:var(--primary)}
    .split{display:grid;grid-template-columns:1fr 1fr;gap:18px}
    @media (max-width:900px){.split{grid-template-columns:1fr}}

    .list{list-style:none;margin:0;padding:0}
    .list li{display:flex;justify-content:space-between;gap:10px;padding:10px 0;border-top:1px dashed var(--ring)}
    .list li:first-child{border-top:none}
    .muted{color:var(--muted);font-size:12px}
    .chart-wrap{height:320px}
    /* Warna Chart.js pakai primary & accent */
  </style>

  {{-- ==== BODY ==== --}}
  <div class="wrap">
    <div class="container">

      {{-- Profil Dosen --}}
      <div class="card pad" style="margin-bottom:20px">
        <div class="profile">
          <div class="avatar">
            {{ strtoupper(substr(($dosen->nama ?? auth()->user()->name ?? 'U'),0,1)) }}
          </div>
          <div>
            <p class="name">{{ $dosen->nama ?? auth()->user()->name }}</p>
            <p class="email">{{ $dosen->email ?? auth()->user()->email }}</p>
          </div>
        </div>
      </div>

      {{-- Aksi Cepat: Kelola Penelitian & Pengabdian --}}
      <div class="card pad" style="margin-bottom:20px">
        <div style="display:flex;gap:12px;flex-wrap:wrap">
          <a href="{{ route('dosen.penelitian.index') }}"
   class="btn btn-primary text-decoration-none">
   Kelola Penelitian
</a>

<a href="{{ route('dosen.pengabdian.index') }}"
   class="btn btn-success text-decoration-none">
   Kelola Pengabdian
</a>

          </a>
        </div>
      </div>

      {{-- Ringkasan Metrik --}}
      <div class="grid4" style="margin-bottom:20px">
        @php
          $cards = [
            ['label'=>'Total Penelitian',  'val'=>$totalPenelitian ?? 0],
            ['label'=>'Total Pengabdian',  'val'=>$totalPengabdian ?? 0],
            ['label'=>'Total Dokumentasi', 'val'=>$totalDokumentasi ?? 0],
            ['label'=>'Menunggu Verifikasi','val'=>$menungguVerif ?? 0],
          ];
        @endphp
        @foreach($cards as $c)
        <div class="card metric">
          <div class="label">{{ $c['label'] }}</div>
          <div class="value">{{ $c['val'] }}</div>
        </div>
        @endforeach
      </div>

      {{-- Grafik --}}
      <div class="card pad" style="margin-bottom:20px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
          <h3 class="section-title">Tren 6 Bulan Terakhir</h3>
          <span class="muted">per bulan</span>
        </div>
        <div class="chart-wrap">
          <canvas id="paChart"></canvas>
        </div>
      </div>

      {{-- Daftar Terbaru --}}
      <div class="split">
        <div class="card pad">
          <h3 class="section-title">Penelitian Terbaru</h3>
          <ul class="list">
            @forelse(($recentPenelitian ?? []) as $p)
              <li>
                <span>{{ $p->judul ?? '—' }}</span>
                <span class="muted">{{ optional($p->created_at)->diffForHumans() }}</span>
              </li>
            @empty
              <li><span class="muted">Tidak ada data</span></li>
            @endforelse
          </ul>
        </div>
        <div class="card pad">
          <h3 class="section-title">Pengabdian Terbaru</h3>
          <ul class="list">
            @forelse(($recentPengabdian ?? []) as $g)
              <li>
                <span>{{ $g->judul ?? '—' }}</span>
                <span class="muted">{{ optional($g->created_at)->diffForHumans() }}</span>
              </li>
            @empty
              <li><span class="muted">Tidak ada data</span></li>
            @endforelse
          </ul>
        </div>
      </div>

    </div>
  </div>

  {{-- ==== SCRIPT Chart.js ==== --}}
  @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    @php
      $labels   = $chartLabels     ?? ['Apr','Mei','Jun','Jul','Agu','Sep'];
      $peneliti = $chartPenelitian ?? [2,4,3,5,4,6];
      $pengabdi = $chartPengabdian ?? [1,2,2,3,4,3];
    @endphp
    <script>
      const ctx = document.getElementById('paChart');
      if (ctx) {
        new Chart(ctx, {
          type: 'line',
          data: {
            labels: @json($labels),
            datasets: [
              { label:'Penelitian',
                data:@json($peneliti),
                borderColor:'var(--primary)',
                backgroundColor:'rgba(32,80,160,0.1)',
                borderWidth:2,
                tension:.35,
                fill:true },
              { label:'Pengabdian',
                data:@json($pengabdi),
                borderColor:'var(--accent)',
                backgroundColor:'rgba(49,165,127,0.1)',
                borderWidth:2,
                tension:.35,
                fill:true }
            ]
          },
          options: {
            plugins:{ legend:{ display:true } },
            scales:{ y:{ beginAtZero:true }, x:{ grid:{ display:false } } }
          }
        });
      }
    </script>
  @endpush
</x-app-layout>
