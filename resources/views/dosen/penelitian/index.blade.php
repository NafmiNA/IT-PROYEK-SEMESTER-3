<x-app-layout>
  <x-slot name="header">
    <div class="d-flex align-items-center justify-content-between">
      <h2 class="h4 mb-0">Kelola Penelitian</h2>
      <a href="{{ route('dosen.penelitian.create') }}" class="btn btn-primary btn-sm">
        + Tambah Penelitian
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
      <div class="card-header bg-light fw-semibold">Daftar Penelitian</div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width:60%">Judul</th>
              <th class="text-nowrap">Tahun</th>
              <th class="text-nowrap">Status</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
          @forelse($penelitian as $p)
            <tr>
              <td>
                <div class="fw-semibold">{{ $p->judul }}</div>
                <div class="small text-muted">{{ $p->created_at?->diffForHumans() }}</div>
              </td>
              <td>{{ $p->tahun }}</td>
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
                <a href="{{ route('dosen.penelitian.show', $p) }}" class="btn btn-outline-secondary btn-sm">
                  Detail
                </a>
                <a href="{{ route('dosen.penelitian.edit', $p) }}" class="btn btn-outline-primary btn-sm">
                  Edit
                </a>
                <form action="{{ route('dosen.penelitian.destroy', $p) }}"
                      method="POST" class="d-inline"
                      onsubmit="return confirm('Hapus penelitian ini?');">
                  @csrf @method('DELETE')
                  <button class="btn btn-outline-danger btn-sm">Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center text-muted py-4">Tidak ada data</td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>

      @if(method_exists($penelitian,'links'))
        <div class="card-footer bg-white">
          {{ $penelitian->links() }}
        </div>
      @endif
    </div>
  </div>
</x-app-layout>
