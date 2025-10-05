<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="text-xl font-semibold text-gray-800">Kelola Penelitian</h2>
      <a href="{{ route('dosen.penelitian.create') }}"
         class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 shadow">
        + Tambah Penelitian
      </a>
    </div>
  </x-slot>

  <div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

      {{-- BOX: DAFTAR PENELITIAN --}}
      <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-100">
          <h3 class="text-lg font-semibold text-gray-800">Daftar Penelitian</h3>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
            <thead class="bg-gray-50">
              <tr class="text-left text-gray-600 font-medium">
                <th class="px-6 py-3 w-1/2">Judul</th>
                <th class="px-6 py-3 text-center">Tahun</th>
                <th class="px-6 py-3 text-center">Status</th>
                <th class="px-6 py-3 text-center">Aksi</th>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 bg-white">
              @forelse ($penelitians as $penelitian)
              <tr class="hover:bg-blue-50 transition duration-200">
                {{-- JUDUL --}}
                <td class="px-6 py-4">
                  <div class="font-semibold text-gray-900">{{ $penelitian->judul }}</div>
                  <div class="text-xs text-gray-500 mt-1">{{ $penelitian->created_at->diffForHumans() }}</div>
                </td>

                {{-- TAHUN --}}
                <td class="px-6 py-4 text-center text-gray-700">{{ $penelitian->tahun }}</td>

                {{-- STATUS --}}
                <td class="px-6 py-4 text-center">
                  @if ($penelitian->status === 'Menunggu')
                    <span class="px-3 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">Menunggu</span>
                  @elseif ($penelitian->status === 'Disetujui')
                    <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Disetujui</span>
                  @elseif ($penelitian->status === 'Ditolak')
                    <span class="px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Ditolak</span>
                  @else
                    <span class="px-3 py-1 text-xs font-semibold text-gray-600 bg-gray-100 rounded-full">Draft</span>
                  @endif
                </td>

                {{-- AKSI --}}
                <td class="px-6 py-4 text-center space-x-2">
                  <a href="{{ route('dosen.penelitian.show', $penelitian->id) }}"
                     class="inline-block px-3 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded-md hover:bg-blue-200">
                    Detail
                  </a>
                  <a href="{{ route('dosen.penelitian.edit', $penelitian->id) }}"
                     class="inline-block px-3 py-1 text-xs font-medium text-yellow-600 bg-yellow-100 rounded-md hover:bg-yellow-200">
                    Edit
                  </a>
                  <form action="{{ route('dosen.penelitian.destroy', $penelitian->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Yakin ingin menghapus data ini?')"
                            class="px-3 py-1 text-xs font-medium text-red-600 bg-red-100 rounded-md hover:bg-red-200">
                      Hapus
                    </button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">Belum ada data penelitian.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</x-app-layout>
