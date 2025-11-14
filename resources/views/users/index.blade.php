<x-app-layout>
    {{-- Ini akan mengisi bagian 'header' di layout Anda --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Menampilkan notifikasi sukses (jika ada) --}}
                    @if (session('success'))
                        <div class="mb-4 p-3 rounded bg-emerald-50 text-emerald-700 border border-emerald-200">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- =================================================================== --}}
                    {{-- PERUBAHAN: Membungkus tombol dalam Flexbox --}}
                    {{-- =================================================================== --}}
                    <div class="flex items-center gap-4 mb-4">
                        
                        {{-- Tombol Kembali (BARU) --}}
                        <a href="{{ route('admin.dashboard') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-full px-5 py-2 text-sm font-semibold
                                  bg-white text-gray-700 hover:bg-gray-100 border border-gray-300
                                  transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>

                        {{-- Tombol untuk 'tambah akun' (Lama) --}}
                        {{-- PERHATIKAN: Rute ini mengarah ke 'admin.users.create' --}}
                        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 rounded-full bg-[#2050A0] px-5 py-2 text-sm font-semibold text-white shadow-md transition hover:bg-[#163B78]">
                            <span class="grid h-6 w-6 place-content-center rounded-full bg-white/15 text-lg">+</span>
                            Tambah User Baru
                        </a>
                    </div>
                    {{-- =================================================================== --}}


                    {{-- Tabel untuk 'lihat akun' --}}
                    <table class="table-auto w-full border-collapse border border-gray-400">
                        <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500">
                            <tr>
                                <th class="border border-gray-300 px-4 py-3 text-left">Nama</th>
                                <th class="border border-gray-300 px-4 py-3 text-left">Email</th>
                                <th class="border border-gray-300 px-4 py-3 text-left">Role</th>
                                <th class="border border-gray-300 px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white text-sm text-gray-700">
                            {{-- Loop data $users (dari UserController@index) --}}
                            @forelse ($users as $user)
                            <tr class="transition hover:bg-gray-50">
                                <td class="border border-gray-300 px-4 py-2 font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $user->email }}</td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($user->role == 'admin') bg-rose-100 text-rose-700
                                        @elseif($user->role == 'dosen') bg-blue-100 text-blue-700
                                        @elseif($user->role == 'mahasiswa') bg-emerald-100 text-emerald-700
                                        @else bg-gray-100 text-gray-700
                                        @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <div class="flex justify-end gap-2 text-xs font-semibold">
                                        {{-- Tombol untuk 'edit akun' --}}
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center gap-2 rounded-full border border-amber-300 px-3 py-1 text-amber-600 transition hover:bg-amber-500 hover:text-white">
                                            Edit
                                        </a>
                                        
                                        {{-- Tombol untuk 'hapus akun' --}}
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus user ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-rose-300 px-3 py-1 text-rose-600 transition hover:bg-rose-500 hover:text-white">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">
                                    Belum ada data user.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>