<x-app-layout>
<div class="container mx-auto px-4 py-6">

    {{-- =================================================================== --}}
    {{-- TOMBOL KEMBALI (BARU DITAMBAHKAN) --}}
    {{-- =================================================================== --}}
    <a href="{{ route('admin.dashboard') }}"
       class="inline-flex items-center justify-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold
              bg-white text-gray-700 hover:bg-gray-100 border border-gray-300
              transition-all duration-200 shadow-sm hover:shadow-md mb-4">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Dashboard
    </a>
    {{-- =================================================================== --}}


    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Prestasi (Global)</h1>
            <p class="text-gray-600">Melihat data prestasi dari semua dosen</p>
        </div>
        {{-- MODIFIKASI: Tombol "Tambah Prestasi" dihapus untuk Admin --}}
        {{-- Sesuai Use Case, Admin tidak menginput data ini --}}
    </div>

    <!-- Alert -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <!-- Current Year Stats (Dihilangkan karena data Admin bersifat global) -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-6 mb-6 text-white">
        <h2 class="text-xl font-bold mb-4">Riwayat Prestasi (Semua Dosen)</h2>
        <p>Menampilkan semua data prestasi yang tercatat di sistem.</p>
    </div>

    <!-- History Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Riwayat Prestasi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        {{-- TAMBAHAN BARU: Kolom Dosen --}}
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dosen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Publikasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hibah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Skor SINTA</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Buku</th>
                        {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th> --}}
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($prestasi as $p)
                    <tr>
                        {{-- TAMBAHAN BARU: Menampilkan nama dosen --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $p->dosen->nama ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $p->tahun }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $p->publikasi }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format($p->hibah, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $p->skor_sinta > 100 ? 'bg-green-100 text-green-800' : ($p->skor_sinta > 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ $p->skor_sinta }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $p->buku }}
                        </td>
                        {{-- MODIFIKASI: Tombol Edit dihapus untuk Admin --}}
                        {{-- <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                        </td> --}}
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Belum ada data prestasi dari dosen manapun.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODIFIKASI: Modal Tambah dan Edit dihapus --}}

{{-- MODIFIKASI: Script Modal dihapus --}}
</x-app-layout>