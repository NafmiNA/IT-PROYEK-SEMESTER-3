<x-app-layout>
  <x-slot name="header">
    <div class="d-flex align-items-center justify-content-between">
      <h2 class="h4 mb-0">Kelola Pengabdian</h2>
      <a href="{{ route('dosen.pengabdian.create') }}" class="btn btn-primary btn-sm">
        + Tambah Pengabdian
      </a>
    </div>
  </x-slot>

  <div class="container py-4">

    {{-- Alert flash --}}
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <div class="card shadow-sm">
      <div class="card-header bg-light fw-semibold">Daftar Pengabdian</div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width:35%">Judul</th>
              <th class="text-nowrap">Tahun</th>
              <th class="text-nowrap">Skema</th>
              <th class="text-nowrap">Sumber Dana</th>
              <th class="text-nowrap">Dana</th>
              <th class="text-nowrap">Status</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
          @forelse($pengabdian as $p)
            <tr>
              <td>
                <div class="fw-semibold">{{ $p->judul }}</div>
                <div class="small text-muted">{{ $p->created_at?->diffForHumans() }}</div>
              </td>
              <td>{{ $p->tahun }}</td>
              <td>{{ $p->skema ?? '-' }}</td>
              <td>{{ $p->sumber_dana ?? '-' }}</td>
              <td>
                @if($p->dana)
                  Rp {{ number_format($p->dana, 0, ',', '.') }}
                @else
                  -
                @endif
              </td>
              <td>
                @php
                  $badge = [
                    'Menunggu' => 'bg-warning text-dark',
                    'Disetujui'=> 'bg-success',
                    'Ditolak'  => 'bg-danger',
                    'Draft'    => 'bg-secondary'
                  ][$p->status] ?? 'bg-secondary';
                @endphp
                <span class="badge {{ $badge }}">{{ $p->status }}</span>
              </td>
              <td class="text-end">
                <a href="{{ route('dosen.pengabdian.show', $p) }}" class="btn btn-outline-secondary btn-sm">
                  Detail
                </a>
                <a href="{{ route('dosen.pengabdian.edit', $p) }}" class="btn btn-outline-primary btn-sm">
                  Edit
                </a>
                <form action="{{ route('dosen.pengabdian.destroy', $p) }}"
                      method="POST" class="d-inline"
                      onsubmit="return confirm('Hapus pengabdian ini?');">
                  @csrf @method('DELETE')
                  <button class="btn btn-outline-danger btn-sm">Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center text-muted py-4">Tidak ada data</td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>

      @if(method_exists($pengabdian,'links'))
        <div class="card-footer bg-white">
          {{ $pengabdian->links() }}
        </div>
      @endif
    </div>
  </div>
</x-app-layout>
