<x-app-layout>
  <x-slot name="header">
    <div class="d-flex align-items-center justify-content-between">
      <h2 class="h4 mb-0">Detail Pengabdian</h2>
      <div class="d-flex gap-2">
        <a href="{{ route('dosen.pengabdian.edit', $pengabdian) }}" class="btn btn-outline-primary btn-sm">Edit</a>
        <a href="{{ route('dosen.pengabdian.index') }}" class="btn btn-light btn-sm">Kembali</a>
      </div>
    </div>
  </x-slot>

  <div class="container py-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title mb-3">{{ $pengabdian->judul }}</h5>

        <div class="row g-3 small">
          <div class="col-md-6"><strong>Tahun</strong><br>{{ $pengabdian->tahun }}</div>
          <div class="col-md-6"><strong>Status</strong><br>{{ $pengabdian->status }}</div>
          <div class="col-md-6"><strong>Bidang</strong><br>{{ $pengabdian->bidang ?? '—' }}</div>
          <div class="col-md-6">
            <strong>Dana</strong><br>
            {{ $pengabdian->dana ? 'Rp '.number_format($pengabdian->dana,0,',','.') : '—' }}
          </div>
          <div class="col-md-6">
            <strong>Ketua</strong><br>
            {{ $pengabdian->ketua?->nama ?? '—' }}
          </div>
          <div class="col-md-6">
            <strong>Dibuat</strong><br>
            {{ optional($pengabdian->created_at)->format('d M Y H:i') }}
          </div>
        </div>

        <hr class="my-4">

        <h6 class="mb-2">Anggota</h6>
        @php
          $anggota = $pengabdian->dosenTerlibat
                      ->filter(fn($d) => $d->pivot?->peran !== 'Ketua');
        @endphp
        @if($anggota->isEmpty())
          <div class="text-muted">Tidak ada anggota.</div>
        @else
          <ul class="mb-0">
            @foreach($anggota as $d)
              <li>{{ $d->nama }} <span class="text-muted">({{ $d->email }})</span></li>
            @endforeach
          </ul>
        @endif

        <hr class="my-4">

        <h6 class="mb-2">Dokumentasi</h6>
        @if($pengabdian->dokumentasi->isEmpty())
          <div class="text-muted">Belum ada dokumentasi.</div>
        @else
          <div class="d-flex flex-wrap gap-3">
            @foreach($pengabdian->dokumentasi as $doc)
              <div class="border rounded p-2 text-center">
                <img
                  src="{{ asset('storage/'.$doc->gdrive_path) }}"
                  alt="{{ $doc->file_name }}"
                  style="height:120px;object-fit:cover;border-radius:.5rem"
                >
                <div class="small mt-2 text-truncate" style="max-width:160px">{{ $doc->file_name }}</div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>
</x-app-layout>
