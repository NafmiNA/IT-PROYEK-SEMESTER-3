<x-app-layout>
  <x-slot name="header">
    <div class="d-flex align-items-center justify-content-between">
      <h2 class="h4 mb-0">Detail Penelitian</h2>
      <a href="{{ route('dosen.penelitian.index') }}" class="btn btn-link">← Kembali</a>
    </div>
  </x-slot>

  <div class="container py-4">
    <div class="card shadow-sm p-4">
      <h4 class="fw-semibold mb-3">{{ $penelitian->judul }}</h4>

      <div class="row g-3 small">
        <div class="col-md-6"><b>Tahun:</b> {{ $penelitian->tahun }}</div>
        <div class="col-md-6"><b>Skema:</b> {{ $penelitian->skema ?? '—' }}</div>
        <div class="col-md-6"><b>Sumber Dana:</b> {{ $penelitian->sumber_dana ?? '—' }}</div>
        <div class="col-md-6"><b>Dana:</b> 
          {{ $penelitian->dana ? 'Rp '.number_format($penelitian->dana, 0, ',', '.') : '—' }}
        </div>
        <div class="col-md-6"><b>Status:</b> {{ $penelitian->status }}</div>
        <div class="col-md-6"><b>Dosen Ketua:</b> {{ $penelitian->ketua->nama ?? '—' }}</div>
      </div>

      <hr>

      <h6>Anggota Penelitian</h6>
      @if($penelitian->dosens->where('pivot.peran','Anggota')->count())
        <ul class="mb-3">
          @foreach($penelitian->dosens->where('pivot.peran','Anggota') as $anggota)
            <li>{{ $anggota->nama }} ({{ $anggota->email }})</li>
          @endforeach
        </ul>
      @else
        <p class="text-muted small">Belum ada anggota terdaftar.</p>
      @endif

      <hr>

      <h6>Dokumentasi</h6>
      @if($penelitian->dokumentasi->count())
        <div class="row g-3">
          @foreach($penelitian->dokumentasi as $dok)
            <div class="col-md-4">
              <div class="border rounded p-2 text-center">
                <img src="{{ asset('storage/'.$dok->gdrive_path) }}" 
                     class="img-fluid rounded mb-2" alt="Dokumentasi">
                <div class="small text-muted">{{ $dok->file_name }}</div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <p class="text-muted small">Belum ada dokumentasi diunggah.</p>
      @endif
    </div>
  </div>
</x-app-layout>
