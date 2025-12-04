@extends('layouts.mahasiswa')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h4 class="text-2xl font-semibold text-gray-800 mb-6">Dokumentasi Saya</h4>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="mb-6">
        <a href="{{ route('mahasiswa.dokumentasi.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
            + Tambah Dokumentasi
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diupload Pada</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($dokumentasi as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->judul }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 hover:text-blue-900">
                                <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="underline">Lihat File</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <form action="{{ route('mahasiswa.dokumentasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus dokumentasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Belum ada dokumentasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection