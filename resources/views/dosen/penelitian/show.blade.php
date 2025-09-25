<x-app-layout>
  <x-slot name="header">
    <div class="d-flex align-items-center justify-content-between">
      <h2 class="h5 mb-0">Detail Penelitian</h2>
      <a href="{{ route('dosen.penelitian.index') }}" class="link-secondary">← Kembali</a>
    </div>
  </x-slot>

  <div class="container py-4">
    <div class="card shadow-sm mx-auto" style="max-width: 860px;">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div>
            <div class="text-muted small">Judul</div>
            <h5 class="mb-0">{{ $penelitian->judul }}</h5>
          </div>
          @php
            $badge = [
              'Menunggu' => 'bg-warning text-dark',
              'Disetujui'=> 'bg-success',
              'Ditolak'  => 'bg-danger',
              'Draft'    => 'bg-secondary'
            ][$penelitian->status] ?? 'bg-secondary';
          @endphp
          <span class="badge {{ $badge }} ms-2">{{ $penelitian->status }}</span>
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <div class="text-muted small">Tahun</div>
            <div class="fw-medium">{{ $penelitian->tahun }}</div>
          </div>
          <div class="col-md-6">
            <div class="text-muted small">Skema</div>
            <div class="fw-medium">{{ $penelitian->skema ?? '—' }}</div>
          </div>
          <div class="col-md-6">
            <div class="text-muted small">Sumber Dana</div>
            <div class="fw-medium">{{ $penelitian->sumber_dana ?? '—' }}</div>
          </div>
          <div class="col-md-6">
            <div class="text-muted small">Dana</div>
            <div class="fw-medium">
              {{ $penelitian->dana ? 'Rp '.number_format($penelitian->dana,0,',','.') : '—' }}
            </div>
          </div>
        </div>

        <hr class="my-4">

        <div class="text-muted small">
          Dibuat: {{ optional($penelitian->created_at)->format('d M Y H:i') }} •
          Diperbarui: {{ optional($penelitian->updated_at)->format('d M Y H:i') }}
        </div>

        <div class="d-flex gap-2 mt-3">
          <a href="{{ route('dosen.penelitian.edit', $penelitian) }}" class="btn btn-primary">Edit</a>

          <form action="{{ route('dosen.penelitian.destroy', $penelitian) }}" method="POST"
                onsubmit="return confirm('Hapus penelitian ini?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger">Hapus</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
