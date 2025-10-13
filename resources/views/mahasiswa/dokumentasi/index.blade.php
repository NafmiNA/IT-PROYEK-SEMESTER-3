@extends('layouts.mahasiswa')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900">Dokumentasi Saya</h2>
            <p class="text-sm text-gray-500">Kelola semua file yang telah Anda unggah</p>
        </div>
        <a href="{{ route('mahasiswa.dashboard') }}" class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-600 shadow-sm transition hover:bg-gray-100">
            ← Kembali ke Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gray-50 text-xs font-semibold text-gray-500">
                <tr>
                    <th class="px-4 py-3 text-left">Nama File</th>
                    <th class="px-4 py-3 text-left">Konteks</th>
                    <th class="px-4 py-3 text-left">Diunggah</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($items as $doc)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <a href="{{ asset('storage/'.$doc->gdrive_path) }}" target="_blank" class="text-blue-700 hover:underline">
                            {{ $doc->file_name ?? basename($doc->gdrive_path) }}
                        </a>
                    </td>
                    <td class="px-4 py-3">
                        @if($doc->penelitian)
                            <span class="text-xs rounded-full bg-blue-100 text-blue-700 px-2 py-0.5 font-semibold">Penelitian</span>
                            <span class="ml-2 text-gray-700">{{ $doc->penelitian->judul }}</span>
                        @elseif($doc->pengabdian)
                            <span class="text-xs rounded-full bg-emerald-100 text-emerald-700 px-2 py-0.5 font-semibold">Pengabdian</span>
                            <span class="ml-2 text-gray-700">{{ $doc->pengabdian->judul }}</span>
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $doc->created_at?->diffForHumans() }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('mahasiswa.dokumentasi.edit', $doc->dokumentasi_id) }}" class="inline-flex items-center px-3 py-1.5 rounded-md bg-orange-500 text-white hover:bg-orange-600">Edit</a>
                            <form action="{{ route('mahasiswa.dokumentasi.destroy', $doc->dokumentasi_id) }}" method="POST" onsubmit="return confirm('Hapus file ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-md bg-rose-600 text-white hover:bg-rose-700">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-10 text-center text-gray-500">Belum ada dokumentasi yang Anda unggah.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ method_exists($items,'links') ? $items->links() : '' }}
    </div>
</div>
@endsection

