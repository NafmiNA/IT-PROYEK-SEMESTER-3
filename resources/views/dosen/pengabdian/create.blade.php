<x-app-layout>

    <div class="max-w-5xl mx-auto px-6 py-8">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <p class="text-xs uppercase tracking-wider text-[#2050A0]/70">Form Pengabdian</p>
                <h2 class="text-2xl font-semibold text-[#2050A0]">Tambah Pengabdian</h2>
                <p class="text-sm text-gray-500">Daftarkan kegiatan pengabdian terbaru beserta tim dan dokumentasinya.</p>
            </div>
            <a href="{{ route('dosen.pengabdian.index') }}"
               class="inline-flex items-center gap-2 rounded-full border border-[#2050A0]/20 bg-white px-4 py-2 text-sm font-semibold text-[#2050A0] shadow-sm transition hover:bg-[#2050A0] hover:text-white">
                <span class="text-lg">←</span>
                <span class="hidden sm:inline">Kembali</span>
            </a>
        </div>
        <form action="{{ route('dosen.pengabdian.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <section class="rounded-3xl border-2 border-gray-200 bg-white p-6 ring-1 ring-gray-200/70 shadow-lg">
                <h3 class="text-lg font-semibold text-[#2050A0]">Informasi Umum</h3>
                <div class="mt-4 grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Judul <span class="text-rose-500">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul') }}" required class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                        @error('judul') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Tahun <span class="text-rose-500">*</span></label>
                        <input type="number" name="tahun" value="{{ old('tahun', now()->year) }}" min="2000" max="2100" required class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                        @error('tahun') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Status</label>
                        <input type="text" name="status" value="Menunggu" readonly
                               class="mt-2 w-full rounded-lg border-slate-300 bg-slate-100 px-3 py-2 text-slate-700 shadow-sm ring-1 ring-inset ring-transparent">
                        @error('status') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Bidang</label>
                        <select name="bidang" class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Pilih bidang</option>
                            @foreach(($bidangOptions ?? []) as $option)
                                <option value="{{ $option }}" @selected(old('bidang') === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('bidang') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Skema</label>
                        <select name="skema" class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Pilih skema</option>
                            @foreach(($skemaOptions ?? []) as $option)
                                <option value="{{ $option }}" @selected(old('skema') === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('skema') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Sumber Dana</label>
                        <select name="sumber_dana" class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Pilih sumber dana</option>
                            @foreach(($sumberDanaOptions ?? []) as $option)
                                <option value="{{ $option }}" @selected(old('sumber_dana') === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('sumber_dana') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Dana (Rp)</label>
                        <input type="number" name="dana" value="{{ old('dana') }}" min="0" class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-1 text-xs text-gray-500">Isi angka tanpa pemisah ribuan.</p>
                        @error('dana') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <section class="rounded-3xl border-2 border-gray-200 bg-white p-6 ring-1 ring-gray-200/70 shadow-lg">
                <h3 class="text-lg font-semibold text-[#2050A0]">Tim Pengabdian</h3>
                <div class="mt-4 space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Ketua <span class="text-rose-500">*</span></label>
                        <select name="ketua_id" required class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Pilih ketua</option>
                            @foreach($dosens as $d)
                                <option value="{{ $d->id }}" @selected(old('ketua_id') == $d->id)>{{ $d->nama }} — {{ $d->email }}</option>
                            @endforeach
                        </select>
                        @error('ketua_id') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Anggota</label>
                        <div id="anggota-wrapper" class="mt-2 space-y-3">
                            <div class="flex gap-3">
                                <select name="anggota_id[]" class="flex-1 rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Pilih anggota</option>
                                    @foreach($dosens as $d)
                                        <option value="{{ $d->id }}">{{ $d->nama }} — {{ $d->email }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-danger" onclick="this.closest('.flex').remove()">Hapus</button>
                            </div>
                        </div>
                        <button type="button" id="tambah-anggota" class="mt-2 btn btn-outline-secondary">+ Tambah Anggota</button>
                        @error('anggota_id') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                        @error('anggota_id.*') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Mahasiswa Pendukung</label>
                        <div id="mahasiswa-wrapper" class="mt-2 space-y-3">
                            <div class="flex gap-3">
                                <select name="mahasiswa_id[]" class="flex-1 rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Pilih mahasiswa (opsional)</option>
                                    @foreach(($mahasiswas ?? []) as $m)
                                        <option value="{{ $m->id }}">{{ $m->nama }} — {{ $m->email }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-danger" onclick="this.closest('.flex').remove()">Hapus</button>
                            </div>
                        </div>
                        <button type="button" id="tambah-mahasiswa" class="mt-2 btn btn-outline-secondary">+ Tambah Mahasiswa</button>
                        @error('mahasiswa_id') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>
            </div>
        </section>

            

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('dosen.pengabdian.index') }}"
                   class="inline-flex items-center gap-2 rounded-full border border-[#2050A0]/20 px-5 py-2 text-sm font-semibold text-[#2050A0] transition hover:bg-[#2050A0]/10">
                    <span class="text-lg">⟲</span>
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-full bg-[#2050A0] px-5 py-2 text-sm font-semibold text-white shadow-md transition hover:bg-[#163B78]">
                    <span class="text-lg">✔</span>
                    Simpan Pengabdian
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

<script>
    document.getElementById('tambah-anggota')?.addEventListener('click', () => {
        const wrap = document.getElementById('anggota-wrapper');
        const tpl = wrap.firstElementChild.cloneNode(true);
        tpl.querySelector('select').value = '';
        wrap.appendChild(tpl);
    });
    document.getElementById('tambah-mahasiswa')?.addEventListener('click', () => {
        const wrap = document.getElementById('mahasiswa-wrapper');
        const tpl = wrap.firstElementChild.cloneNode(true);
        tpl.querySelector('select').value = '';
        wrap.appendChild(tpl);
    });
</script>
