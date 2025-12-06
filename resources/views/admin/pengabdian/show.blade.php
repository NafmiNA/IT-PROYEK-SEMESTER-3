<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50/30 to-gray-50">
        <div class="mx-auto max-w-5xl px-4 py-8">
            
            {{-- Breadcrumb --}}
            <nav class="mb-6 animate-fade">
                <ol class="flex items-center gap-2 text-sm text-gray-600">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition-colors">Dashboard</a></li>
                    <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                    <li><a href="{{ route('admin.pengabdian.index') }}" class="hover:text-blue-600 transition-colors">Pengabdian</a></li>
                    <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                    <li class="font-semibold text-blue-600">Detail Pengabdian</li>
                </ol>
            </nav>

            {{-- Header --}}
            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between mb-8 animate-slide-up">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider 
                            {{ $pengabdian->status === 'Disetujui' ? 'bg-green-100 text-green-700' : 
                              ($pengabdian->status === 'Ditolak' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ $pengabdian->status }}
                        </span>
                        <span class="text-sm text-gray-500 font-medium">{{ $pengabdian->tahun }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 leading-tight">{{ $pengabdian->judul }}</h1>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <a href="{{ route('admin.pengabdian.index') }}" class="px-4 py-2 rounded-lg border-2 border-gray-200 text-gray-600 font-semibold hover:bg-gray-50 transition-colors">
                        Kembali
                    </a>
                    <a href="{{ route('admin.pengabdian.edit', $pengabdian) }}" class="px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 shadow-md transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Data
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade">
                
                {{-- Left Column: Main Info & Docs --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Card 1: Informasi Dasar --}}
                    <section class="rounded-2xl bg-white shadow-lg ring-1 ring-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 flex items-center gap-3">
                            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h2 class="text-lg font-bold text-white">Informasi Pengabdian</h2>
                        </div>
                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Bidang</p>
                                <p class="text-gray-900 font-medium">{{ $pengabdian->bidang ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Skema</p>
                                <p class="text-gray-900 font-medium">{{ $pengabdian->skema ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Sumber Dana</p>
                                <p class="text-gray-900 font-medium">{{ $pengabdian->sumber_dana ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Jumlah Dana</p>
                                <p class="text-emerald-600 font-bold font-mono text-lg">Rp {{ number_format($pengabdian->dana, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </section>

                    {{-- Card 2: Dokumen --}}
                    <section class="rounded-2xl bg-white shadow-lg ring-1 ring-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-4 flex items-center gap-3">
                            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <h2 class="text-lg font-bold text-white">Galeri Dokumentasi</h2>
                        </div>
                        <div class="p-6">
                            @if($pengabdian->dokumentasi && $pengabdian->dokumentasi->count() > 0)
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    @foreach($pengabdian->dokumentasi as $doc)
                                        <a href="{{ Storage::url($doc->gdrive_path) }}" target="_blank" class="group relative aspect-square rounded-lg overflow-hidden bg-gray-100 block ring-1 ring-black/5">
                                            @if(in_array(strtolower(pathinfo($doc->file_name, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                <img src="{{ Storage::url($doc->gdrive_path) }}" alt="Doc" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                                            @else
                                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 p-2">
                                                    <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                    <span class="text-[10px] text-center line-clamp-2">{{ $doc->file_name }}</span>
                                                </div>
                                            @endif
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-3">
                                                <p class="text-white text-xs truncate w-full">{{ $doc->file_name }}</p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                    <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="text-sm text-gray-500">Belum ada dokumentasi.</p>
                                </div>
                            @endif
                        </div>
                    </section>
                </div>

                {{-- Right Column: Team --}}
                <div class="lg:col-span-1 space-y-6">
                    
                    {{-- Card 3: Tim Pelaksana --}}
                    <section class="rounded-2xl bg-white shadow-lg ring-1 ring-gray-200 overflow-hidden sticky top-6">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-4 flex items-center gap-3">
                            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <h2 class="text-lg font-bold text-white">Tim Pengabdian</h2>
                        </div>
                        <div class="p-6 space-y-6">
                            {{-- Ketua --}}
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Ketua Pengabdian</p>
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-blue-50 border border-blue-100">
                                    <div class="w-10 h-10 rounded-full bg-blue-200 text-blue-700 flex items-center justify-center font-bold text-lg">
                                        {{ substr($pengabdian->ketua->nama ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 text-sm">{{ $pengabdian->ketua->nama ?? '-' }}</p>
                                        <p class="text-xs text-blue-600">{{ $pengabdian->ketua->email ?? '' }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Anggota --}}
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Anggota Dosen</p>
                                @if($pengabdian->dosens->where('id', '!=', $pengabdian->dosen_id)->count() > 0)
                                    <div class="space-y-2">
                                        @foreach($pengabdian->dosens->where('id', '!=', $pengabdian->dosen_id) as $dosen)
                                            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                                                <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-xs">
                                                    {{ substr($dosen->nama, 0, 1) }}
                                                </div>
                                                <p class="text-sm font-medium text-gray-700">{{ $dosen->nama }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500 italic pl-2">Tidak ada anggota dosen</p>
                                @endif
                            </div>

                            {{-- Mahasiswa --}}
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Mahasiswa</p>
                                @if($pengabdian->mahasiswas && $pengabdian->mahasiswas->count() > 0)
                                    <div class="space-y-2">
                                        @foreach($pengabdian->mahasiswas as $mhs)
                                            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                                </div>
                                                <p class="text-sm font-medium text-gray-700">{{ $mhs->nama }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500 italic pl-2">Tidak ada mahasiswa terlibat</p>
                                @endif
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            
            <div class="h-12"></div>
        </div>
    </div>
</x-app-layout>