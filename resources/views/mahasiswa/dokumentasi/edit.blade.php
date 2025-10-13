@extends('layouts.mahasiswa')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-semibold text-gray-900">Edit Dokumentasi</h2>
        <a href="{{ route('mahasiswa.dokumentasi.index') }}" class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-600 shadow-sm transition hover:bg-gray-100">← Kembali</a>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="mb-4">
            <p class="text-sm text-gray-500">Nama file saat ini</p>
            <p class="font-semibold text-gray-800">{{ $doc->file_name ?? basename($doc->gdrive_path) }}</p>
            <a href="{{ asset('storage/'.$doc->gdrive_path) }}" target="_blank" class="text-sm text-blue-700 hover:underline">Lihat/unduh</a>
        </div>

        <form action="{{ route('mahasiswa.dokumentasi.update', $doc->dokumentasi_id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ubah nama tampilan (opsional)</label>
                <input type="text" name="file_name" value="{{ old('file_name', $doc->file_name) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ganti file (opsional)</label>
                <input type="file" name="file" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <p class="text-xs text-gray-500 mt-1">Format: jpg, jpeg, png, pdf (maks 4 MB)</p>
            </div>

            <div class="pt-2 flex items-center gap-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Simpan Perubahan</button>
                <a href="{{ route('mahasiswa.dokumentasi.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

