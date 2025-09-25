<x-app-layout>
  <x-slot name="header">
    <div class="d-flex align-items-center justify-content-between">
      <h2 class="h5 mb-0">Edit Penelitian</h2>
      <a href="{{ route('dosen.penelitian.index') }}" class="link-secondary">← Kembali</a>
    </div>
  </x-slot>

  <div class="container py-4">
    <form action="{{ route('dosen.penelitian.update', $penelitian) }}" method="POST" class="mx-auto" style="max-width: 760px;">
      @csrf @method('PUT')

      <div class="card shadow-sm">
        <div class="card-body">
          @include('dosen.penelitian._form', ['penelitian' => $penelitian])
        </div>
      </div>

      <div class="d-flex gap-2 mt-3">
        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('dosen.penelitian.index') }}" class="btn btn-outline-secondary">Batal</a>
      </div>
    </form>
  </div>
</x-app-layout>
<x-app-layout>
  <x-slot name="header">
    <h2>Detail Penelitian</h2>
  </x-slot>

  <div class="p-6 max-w-3xl mx-auto">
    <div class="bg-white shadow rounded-lg p-4 border space-y-4">
      <div class="flex items-center justify-between">
        <h3 class="text-lg font-semibold">{{ $penelitian->judul ?? '—' }}</h3>
        @php
          $badge = match ($penelitian->status) {
            'Diajukan' => 'bg-blue-100 text-blue-700',
            'Disetujui' => 'bg-green-100 text-green-700',
            'Ditolak' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
            };
        @endphp
        <span class="px-2 py-1 text-xs rounded-full {{ $badge }}">  
            {{ $penelitian->status ?? '—' }}
        </span>
      </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
            <div class="text-sm text-gray-500">Tahun</div>
            <div class="font-medium">{{ $penelitian->tahun ?? '—' }}</div>
            </div>
            <div>
            <div class="text-sm text-gray-500">Skema</div>
            <div class="font-medium">{{ $penelitian->skema ?? '—' }}</div>
            </div>
            <div>
            <div class="text-sm text-gray-500">Sumber Dana</div>
            <div class="font-medium">{{ $penelitian->sumber_dana ?? '—' }}</div>
            </div>
            <div>
            <div class="text-sm text-gray-500">Dana</div>
            <div class="font-medium">
                {{ $penelitian->dana ? 'Rp '.number_format($penelitian->dana,0,',','.') : '—' }}
            </div>
            </div>
        </div>
        <div class="text-xs text-gray-500">
        Dibuat: {{ optional($penelitian->created_at)->format('d M Y H:i') }} •
        Diperbarui: {{ optional($penelitian->updated_at)->format('d M Y H:i') }}
        </div>
        <div class="flex items-center gap-3 pt-2">
        <a href="{{ route('dosen.penelitian.edit', $penelitian) }}"
           class="inline-flex items-center px-3 py-2 rounded-lg text-white"
           style="background:#2050A0">
          Edit
        </a>
        <a href="{{ route('dosen.penelitian.index') }}"
           class="inline-flex items-center px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
          Kembali
        </a>
      </div>
    </div>
    </div>
</x-app-layout>
<x-app-layout>