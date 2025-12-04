<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Dokumentasi
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="mb-6">
                <ol class="flex items-center gap-2 text-sm text-gray-600">
                    <li><a href="{{ route('mahasiswa.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                    <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                    <li><a href="{{ route('mahasiswa.dokumentasi.index') }}" class="hover:text-blue-600">Dokumentasi</a></li>
                    <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                    <li class="font-semibold text-blue-600">Detail</li>
                </ol>
            </nav>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-6 pb-4 border-b">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $doc->file_name }}</h3>
                        <div class="flex gap-2">
                            <a href="{{ route('mahasiswa.dokumentasi.edit', $doc->dokumentasi_id) }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            <a href="{{ route('mahasiswa.dokumentasi.index') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                                Kembali
                            </a>
                        </div>
                    </div>

                    <!-- Info Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Nama File -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama File</label>
                            <p class="text-gray-900">{{ $doc->file_name }}</p>
                        </div>

                        <!-- Tipe File -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe File</label>
                            <p class="text-gray-900">{{ $doc->mime }}</p>
                        </div>

                        <!-- Ukuran File -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Ukuran File</label>
                            <p class="text-gray-900">{{ number_format($doc->size / 1024, 2) }} KB</p>
                        </div>

                        <!-- Tanggal Upload -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Upload</label>
                            <p class="text-gray-900">{{ $doc->created_at->format('d M Y, H:i') }}</p>
                        </div>

                        <!-- Terkait Dengan -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Terkait Dengan</label>
                            @if($doc->penelitian_id)
                                <p class="text-gray-900">
                                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">
                                        Penelitian
                                    </span>
                                    {{ $doc->penelitian->judul ?? 'N/A' }}
                                </p>
                            @elseif($doc->pengabdian_id)
                                <p class="text-gray-900">
                                    <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs rounded">
                                        Pengabdian
                                    </span>
                                    {{ $doc->pengabdian->judul ?? 'N/A' }}
                                </p>
                            @else
                                <p class="text-gray-500">Dokumentasi Standalone</p>
                            @endif
                        </div>

                        <!-- Status Upload Google Drive -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Status Google Drive</label>
                            @if($doc->uploaded_to_drive)
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Terupload ke Google Drive
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-600 text-sm rounded-full">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Hanya di Server
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Download Buttons -->
                    <div class="flex flex-wrap gap-3 mt-6 pt-6 border-t">
                        <!-- Download dari Server -->
                        <a href="{{ asset('storage/' . $doc->gdrive_path) }}" 
                           download="{{ $doc->file_name }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download dari Server
                        </a>

                        <!-- View dari Server -->
                        <a href="{{ asset('storage/' . $doc->gdrive_path) }}" 
                           target="_blank"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Lihat File
                        </a>

                        <!-- Download dari Google Drive -->
                        @if($doc->uploaded_to_drive && $doc->drive_file_url)
                            <a href="{{ $doc->drive_file_url }}" 
                               target="_blank"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.545 10.239v3.821h5.445c-.712 2.315-2.647 3.972-5.445 3.972a6.033 6.033 0 110-12.064c1.498 0 2.866.549 3.921 1.453l2.814-2.814A9.969 9.969 0 0012.545 2C7.021 2 2.543 6.477 2.543 12s4.478 10 10.002 10c8.396 0 10.249-7.85 9.426-11.748l-9.426-.013z"/>
                                </svg>
                                Buka di Google Drive
                            </a>
                        @endif
                    </div>

                    <!-- Preview untuk PDF/Image -->
                    @if(in_array(strtolower(pathinfo($doc->file_name, PATHINFO_EXTENSION)), ['pdf', 'jpg', 'jpeg', 'png', 'gif']))
                        <div class="mt-6 pt-6 border-t">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Preview</label>
                            <div class="bg-gray-50 rounded-lg p-4">
                                @if(strtolower(pathinfo($doc->file_name, PATHINFO_EXTENSION)) == 'pdf')
                                    <iframe src="{{ asset('storage/' . $doc->gdrive_path) }}" 
                                            class="w-full h-[600px] border-0 rounded"
                                            type="application/pdf">
                                    </iframe>
                                @else
                                    <img src="{{ asset('storage/' . $doc->gdrive_path) }}" 
                                         alt="{{ $doc->file_name }}"
                                         class="max-w-full h-auto rounded">
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
