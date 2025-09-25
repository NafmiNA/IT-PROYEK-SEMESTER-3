<x-app-layout>
  <x-slot name="header">
    <h2>Kelola Penelitian</h2>
  </x-slot>

  <div class="p-6 max-w-5xl mx-auto">
    <div class="mb-4 flex justify-between">
      <h3 class="text-lg font-semibold">Daftar Penelitian</h3>
      <a href="{{ route('dosen.Pengabdian.create') }}"
         class="px-3 py-2 rounded-lg text-white"
         style="background:#2050A0">
        + Tambah Pengabdian
      </a>
    </div>

    <div class="bg-white shadow rounded-lg p-4 border">
      <ul class="list">
        @forelse(($pengabdian ?? []) as $p)
          <li class="py-2 border-t first:border-t-0">
            <span>{{ $p->judul ?? '—' }}</span>
            <span class="text-gray-500 text-sm">
              {{ optional($p->created_at)->diffForHumans() }}
            </span>
          </li>
        @empty
          <li class="py-4 text-gray-500">Tidak ada data</li>
        @endforelse
      </ul>
    </div>
  </div>
</x-app-layout>
