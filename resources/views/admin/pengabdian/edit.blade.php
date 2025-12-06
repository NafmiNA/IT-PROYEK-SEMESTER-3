<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50/30 to-gray-50">
        <div class="mx-auto max-w-5xl px-4 py-8">
            
            {{-- Breadcrumb Navigation --}}
            <nav class="mb-6 animate-fade">
                <ol class="flex items-center gap-2 text-sm text-gray-600">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition-colors">Dashboard</a></li>
                    <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                    <li><a href="{{ route('admin.pengabdian.index') }}" class="hover:text-blue-600 transition-colors">Pengabdian</a></li>
                    <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                    <li class="font-semibold text-blue-600">Edit Pengabdian</li>
                </ol>
            </nav>

            {{-- Header with Back Button --}}
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-8 animate-slide-up">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.pengabdian.show', $pengabdian) }}" 
                       class="group flex-shrink-0 inline-flex items-center justify-center w-11 h-11 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-md hover:shadow-lg transition-all duration-200"
                       aria-label="Kembali ke Detail">
                         <svg class="w-5 h-5 transform group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                         </svg>
                    </a>
                    <div>
                        <p class="text-xs uppercase tracking-wider text-blue-600/70 font-semibold">Edit Data</p>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Perbarui Pengabdian</h1>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.pengabdian.update', $pengabdian) }}" method="POST" enctype="multipart/form-data" class="space-y-6 animate-fade">
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
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Judul Pengabdian <span class="text-red-600">*</span></label>
                            <input type="text" name="judul" value="{{ old('judul', $pengabdian->judul) }}" required class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 hover:border-gray-400">
                            @error('judul') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Tahun <span class="text-red-600">*</span></label>
                            <input type="number" name="tahun" value="{{ old('tahun', $pengabdian->tahun) }}" min="2000" max="2100" required class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 hover:border-gray-400">
                            @error('tahun') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Status</label>
                            <select name="status" class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 hover:border-gray-400">
                                @foreach(['Draft','Menunggu','Disetujui','Ditolak'] as $status)
                                    <option value="{{ $status }}" @selected(old('status', $pengabdian->status) === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                            @error('status') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Bidang</label>
                            <select name="bidang" class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 hover:border-gray-400">
                                <option value="">Pilih bidang</option>
                                @foreach(($bidangOptions ?? []) as $option)
                                    <option value="{{ $option }}" @selected(old('bidang', $pengabdian->bidang) === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('bidang') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Skema</label>
                            <select name="skema" class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 hover:border-gray-400">
                                <option value="">Pilih skema</option>
                                @foreach(($skemaOptions ?? []) as $option)
                                    <option value="{{ $option }}" @selected(old('skema', $pengabdian->skema) === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('skema') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Sumber Dana</label>
                            <select name="sumber_dana" class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 hover:border-gray-400">
                                <option value="">Pilih sumber dana</option>
                                @foreach(($sumberDanaOptions ?? []) as $option)
                                    <option value="{{ $option }}" @selected(old('sumber_dana', $pengabdian->sumber_dana) === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('sumber_dana') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Dana (Rp)</label>
                            <input type="number" name="dana" value="{{ old('dana', $pengabdian->dana) }}" min="0" class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 hover:border-gray-400">
                            <p class="mt-2 text-xs text-gray-600">Isi angka tanpa pemisah ribuan.</p>
                            @error('dana') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
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
                                <p class="text-sm text-emerald-100">Personil yang terlibat</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Ketua <span class="text-red-600">*</span></label>
                            <select name="ketua_id" required class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 hover:border-gray-400">
                                <option value="">Pilih ketua</option>
                                @foreach($dosens as $d)
                                    <option value="{{ $d->id }}" @selected(old('ketua_id', $pengabdian->ketua?->id) == $d->id)>{{ $d->nama }} — {{ $d->email }}</option>
                                @endforeach
                            </select>
                            @error('ketua_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Anggota Tim</label>
                            <div id="anggota-wrapper" class="space-y-3">
                                @php 
                                    $oldAnggota = old('anggota_id', $anggotaTerpilih ?? []); 
                                    if (empty($oldAnggota)) $oldAnggota = [null]; 
                                @endphp
                                @foreach($oldAnggota as $selectedId)
                                <div class="flex gap-3">
                                    <select name="anggota_id[]" class="flex-1 rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 hover:border-gray-400">
                                        <option value="">Pilih anggota (opsional)</option>
                                        @foreach($dosens as $d)
                                            <option value="{{ $d->id }}" @selected($selectedId == $d->id)>{{ $d->nama }} — {{ $d->email }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="flex-shrink-0 inline-flex items-center justify-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors whitespace-nowrap" onclick="this.closest('.flex').remove()">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Hapus
                                    </button>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" id="tambah-anggota" class="mt-3 inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-sm font-medium rounded-lg border-2 border-emerald-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Tambah Anggota
                            </button>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Mahasiswa Pendukung</label>
                            <div id="mahasiswa-wrapper" class="space-y-3">
                                @php 
                                    $oldMahasiswa = old('mahasiswa_id', $mahasiswaTerpilih ?? []); 
                                    if (empty($oldMahasiswa)) $oldMahasiswa = [null];
                                @endphp
                                @foreach($oldMahasiswa as $selectedId)
                                <div class="flex gap-3">
                                    <select name="mahasiswa_id[]" class="flex-1 rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 hover:border-gray-400">
                                        <option value="">Pilih mahasiswa (opsional)</option>
                                        @foreach(($mahasiswas ?? []) as $m)
                                            <option value="{{ $m->id }}" @selected($selectedId == $m->id)>{{ $m->nama }} — {{ $m->email }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="flex-shrink-0 inline-flex items-center justify-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors whitespace-nowrap" onclick="this.closest('.flex').remove()">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Hapus
                                    </button>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" id="tambah-mahasiswa" class="mt-3 inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-sm font-medium rounded-lg border-2 border-emerald-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Tambah Mahasiswa
                            </button>
                        </div>
                    </div>
                </section>

                {{-- Section 3: Documents --}}
                <section class="rounded-2xl bg-white shadow-lg ring-1 ring-gray-200 overflow-hidden transition-all hover:shadow-xl">
                    <div class="bg-gradient-to-r from-amber-600 to-amber-700 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-lg font-bold text-white">Dokumen & Laporan</h2>
                                <p class="text-sm text-amber-100">Upload dokumentasi kegiatan</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Unggah Dokumentasi (Opsional)</label>
                            <div class="relative">
                                <input type="file" name="dokumentasi[]" multiple accept="image/*,.pdf,.doc,.docx" class="block w-full text-sm text-gray-600 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-600 file:text-white hover:file:bg-amber-700 file:cursor-pointer border-2 border-dashed border-gray-300 rounded-lg px-4 py-8 hover:border-gray-400 transition-colors cursor-pointer bg-gray-50">
                            </div>
                            @error('dokumentasi.*') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

                            @if($pengabdian->dokumentasi->isNotEmpty())
                                <div class="mt-4 rounded-2xl border border-gray-100 bg-gray-50 p-4">
                                    <p class="text-xs uppercase tracking-wide text-gray-400">Dokumentasi tersimpan</p>
                                    <div class="mt-3 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                        @foreach($pengabdian->dokumentasi as $doc)
                                            <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                                                <div class="relative aspect-video bg-gray-100">
                                                    <img src="{{ asset('storage/'.$doc->gdrive_path) }}" alt="{{ $doc->file_name }}" class="h-full w-full object-cover">
                                                </div>
                                                <div class="px-3 py-2 text-xs text-gray-600">
                                                    <p class="font-semibold text-gray-800 truncate" title="{{ $doc->file_name }}">{{ $doc->file_name }}</p>
                                                    <p>{{ number_format(($doc->size ?? 0) / 1024, 0) }} KB</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </section>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 animate-slide-up">
                    <a href="{{ route('admin.pengabdian.show', $pengabdian) }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg border-2 border-gray-300 bg-white text-gray-700 text-sm font-semibold shadow-sm hover:bg-gray-50 hover:border-gray-400 transition-all">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-lg hover:shadow-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
            <div class="h-8"></div>
        </div>
    </div>

    <script>
        document.getElementById('tambah-anggota')?.addEventListener('click', () => {
            const wrapper = document.getElementById('anggota-wrapper');
            const template = wrapper.firstElementChild.cloneNode(true);
            template.querySelector('select').value = '';
            wrapper.appendChild(template);
        });
        document.getElementById('tambah-mahasiswa')?.addEventListener('click', () => {
            const wrapper = document.getElementById('mahasiswa-wrapper');
            const template = wrapper.firstElementChild.cloneNode(true);
            template.querySelector('select').value = '';
            wrapper.appendChild(template);
        });
        document.querySelector('form')?.addEventListener('submit', (e) => {
            const submitBtn = e.target.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...`;
        });
    </script>
</x-app-layout>
