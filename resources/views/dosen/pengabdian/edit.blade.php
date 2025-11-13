<x-app-layout>
    {{-- Modern UX-focused Layout (matching create design) --}}
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50/30 to-gray-50">
        <div class="mx-auto max-w-5xl px-4 py-8">
            
            {{-- Breadcrumb Navigation --}}
            <nav class="mb-6 animate-fade">
                <ol class="flex items-center gap-2 text-sm text-gray-600">
                    <li><a href="{{ route('dosen.dashboard') }}" class="hover:text-blue-600 transition-colors">Dashboard</a></li>
                    <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                    <li><a href="{{ route('dosen.pengabdian.index') }}" class="hover:text-blue-600 transition-colors">Pengabdian</a></li>
                    <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                    <li class="font-semibold text-blue-600">Edit Pengabdian</li>
                </ol>
            </nav>

            {{-- Header with Back Button --}}
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-8 animate-slide-up">
                <div class="flex items-center gap-3">
                    <a href="{{ route('dosen.pengabdian.show', $pengabdian) }}" 
                       class="group flex-shrink-0 inline-flex items-center justify-center w-11 h-11 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-md hover:shadow-lg transition-all duration-200"
                       aria-label="Kembali">
                        <svg class="w-5 h-5 transform group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <p class="text-xs uppercase tracking-wider text-blue-600/70 font-semibold">Edit Pengabdian</p>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Perbarui Data Pengabdian</h1>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('dosen.pengabdian.update', $pengabdian) }}" enctype="multipart/form-data" class="space-y-6 animate-fade">
                @csrf
                @method('PUT')

                {{-- Section 1: Basic Information --}}
                <section class="rounded-2xl bg-white shadow-lg ring-1 ring-gray-200 overflow-hidden transition-all hover:shadow-xl">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-lg font-bold text-white">Informasi Dasar</h2>
                                <p class="text-sm text-blue-100">Detail utama pengabdian</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                        {{-- Judul --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-900 mb-2">
                                Judul Pengabdian <span class="text-red-600">*</span>
                            </label>
                            <input type="text" 
                                   name="judul" 
                                   value="{{ old('judul', $pengabdian->judul) }}" 
                                   required 
                                   class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 hover:border-gray-400">
                            @error('judul') 
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Tahun --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">
                                Tahun <span class="text-red-600">*</span>
                            </label>
                            <input type="number" 
                                   name="tahun" 
                                   value="{{ old('tahun', $pengabdian->tahun) }}" 
                                   min="2000" 
                                   max="2100" 
                                   required 
                                   class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 hover:border-gray-400">
                            @error('tahun') 
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Status</label>
                            <select name="status" class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 hover:border-gray-400">
                                @foreach(['Draft','Menunggu','Disetujui','Ditolak'] as $status)
                                    <option value="{{ $status }}" @selected(old('status', $pengabdian->status) === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                            @error('status') 
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Bidang --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Bidang</label>
                            <select name="bidang" class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 hover:border-gray-400">
                                <option value="">Pilih bidang</option>
                                @foreach(($bidangOptions ?? []) as $option)
                                    <option value="{{ $option }}" @selected(old('bidang', $pengabdian->bidang) === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('bidang') 
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Skema --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Skema</label>
                            <select name="skema" class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 hover:border-gray-400">
                                <option value="">Pilih skema</option>
                                @foreach(($skemaOptions ?? []) as $option)
                                    <option value="{{ $option }}" @selected(old('skema', $pengabdian->skema) === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('skema') 
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Sumber Dana --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Sumber Dana</label>
                            <select name="sumber_dana" class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 hover:border-gray-400">
                                <option value="">Pilih sumber dana</option>
                                @foreach(($sumberDanaOptions ?? []) as $option)
                                    <option value="{{ $option }}" @selected(old('sumber_dana', $pengabdian->sumber_dana) === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('sumber_dana') 
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Dana --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Dana (Rp)</label>
                            <input type="text" 
                                   name="dana" 
                                   value="{{ old('dana', $pengabdian->dana) }}" 
                                   class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 hover:border-gray-400">
                            <p class="mt-2 text-xs text-gray-600">Isi angka tanpa titik/koma</p>
                            @error('dana') 
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </section>

                {{-- Section 2: Team --}}
                <section class="rounded-2xl bg-white shadow-lg ring-1 ring-gray-200 overflow-hidden transition-all hover:shadow-xl">
                    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-lg font-bold text-white">Tim Pengabdian</h2>
                                <p class="text-sm text-emerald-100">Pelaksana dan pendukung</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        {{-- Ketua --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">
                                Ketua <span class="text-red-600">*</span>
                            </label>
                            <select name="ketua_id" required class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 hover:border-gray-400">
                                <option value="">Pilih ketua pengabdian</option>
                                @foreach($dosens as $d)
                                    <option value="{{ $d->id }}" @selected(old('ketua_id', $pengabdian->ketua?->id) == $d->id)>{{ $d->nama }} — {{ $d->email }}</option>
                                @endforeach
                            </select>
                            @error('ketua_id') 
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Anggota --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Anggota</label>
                            <select name="anggota_id[]" multiple class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 hover:border-gray-400">
                                @foreach($dosens as $d)
                                    <option value="{{ $d->id }}" @selected(in_array($d->id, old('anggota_id', $anggotaTerpilih ?? [])))>{{ $d->nama }} — {{ $d->email }}</option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-xs text-gray-600">Tekan Ctrl/Cmd untuk memilih lebih dari satu</p>
                            @error('anggota_id') 
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Mahasiswa --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Mahasiswa Pendukung</label>
                            <select name="mahasiswa_id[]" multiple class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 hover:border-gray-400">
                                @foreach(($mahasiswas ?? []) as $m)
                                    <option value="{{ $m->id }}" @selected(in_array($m->id, old('mahasiswa_id', $mahasiswaTerpilih ?? [])))>{{ $m->nama }} — {{ $m->email }}</option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-xs text-gray-600">Tekan Ctrl/Cmd untuk memilih lebih dari satu</p>
                            @error('mahasiswa_id') 
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </section>

                {{-- Section 3: Documents --}}
                <section class="rounded-2xl bg-white shadow-lg ring-1 ring-gray-200 overflow-hidden transition-all hover:shadow-xl">
                    <div class="bg-gradient-to-r from-amber-600 to-amber-700 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-lg font-bold text-white">Dokumentasi</h2>
                                <p class="text-sm text-amber-100">Foto dan dokumen pendukung</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        {{-- Upload File --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Unggah Dokumentasi Baru</label>
                            <div class="relative">
                                <input type="file" 
                                       name="dokumentasi[]" 
                                       multiple
                                       accept="image/*,application/pdf,.doc,.docx" 
                                       class="block w-full text-sm text-gray-600 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-600 file:text-white hover:file:bg-amber-700 file:cursor-pointer border-2 border-dashed border-gray-300 rounded-lg px-4 py-8 hover:border-gray-400 transition-colors cursor-pointer bg-gray-50">
                            </div>
                            <p class="mt-2 text-xs text-gray-600 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                Format: JPG, PNG, PDF, DOC, DOCX • Maksimal 5MB per file
                            </p>
                            @error('dokumentasi.*') 
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Existing Documentation --}}
                        @if($pengabdian->dokumentasi->isNotEmpty())
                            <div class="rounded-xl border-2 border-gray-200 bg-gray-50 p-4">
                                <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-3">Dokumentasi Tersimpan</p>
                                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    @foreach($pengabdian->dokumentasi as $doc)
                                        <div class="overflow-hidden rounded-xl border-2 border-gray-200 bg-white shadow-sm hover:shadow-md transition-all">
                                            <div class="relative aspect-video bg-gray-100">
                                                <img src="{{ asset('storage/'.$doc->gdrive_path) }}" 
                                                     alt="{{ $doc->file_name }}" 
                                                     class="h-full w-full object-cover">
                                            </div>
                                            <div class="px-3 py-2 text-xs">
                                                <p class="font-semibold text-gray-800 truncate" title="{{ $doc->file_name }}">
                                                    {{ $doc->file_name }}
                                                </p>
                                                <p class="text-gray-600">{{ number_format(($doc->size ?? 0) / 1024, 0) }} KB</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- Action Buttons --}}
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 animate-slide-up">
                    <a href="{{ route('dosen.pengabdian.show', $pengabdian) }}"
                       class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg border-2 border-gray-300 bg-white text-gray-700 text-sm font-semibold shadow-sm hover:bg-gray-50 hover:border-gray-400 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-lg hover:shadow-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>

            {{-- Footer Spacing --}}
            <div class="h-8"></div>
        </div>
    </div>
</x-app-layout>
