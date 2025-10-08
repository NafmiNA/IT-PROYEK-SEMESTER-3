<x-app-layout>

    <div class="py-8 bg-gray-50">
        <div class="mx-auto max-w-5xl px-4">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <p class="text-xs uppercase tracking-wider text-[#2050A0]/70">Form Penelitian</p>
                    <h2 class="text-2xl font-semibold text-[#2050A0]">Tambah Penelitian</h2>
                </div>
                <a href="{{ route('dosen.penelitian.index') }}"
                   class="inline-flex items-center gap-2 rounded-full border border-[#2050A0]/20 bg-white px-4 py-2 text-sm font-semibold text-[#2050A0] shadow-sm transition hover:bg-[#2050A0] hover:text-white">
                    <span class="text-lg">←</span>
                    <span class="hidden sm:inline">Kembali</span>
                </a>
            </div>
            <form method="POST" action="{{ route('dosen.penelitian.store') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <section class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
                    <div class="p-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Judul <span class="text-rose-600">*</span></label>
                            <input type="text" name="judul" value="{{ old('judul') }}" required class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500" placeholder="Masukkan judul penelitian">
                            <p class="mt-2 text-xs text-slate-500">Tulis singkat, jelas, dan spesifik.</p>
                            @error('judul') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tahun <span class="text-rose-600">*</span></label>
                            <input type="number" name="tahun" value="{{ old('tahun', now()->year) }}" min="2000" max="2100" required class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                            @error('tahun') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
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
                            <input type="text" name="dana" value="{{ old('dana') }}" class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500" placeholder="15000000">
                            <p class="mt-2 text-xs text-slate-500">Isi angka tanpa titik/koma (format akan diolah di backend).</p>
                            @error('dana') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Tempat Terbit Jurnal</label>
                            <input type="text" name="tempat_terbit" value="{{ old('tempat_terbit') }}" class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500" placeholder="Nama jurnal atau prosiding">
                        </div>
                    </div>
                </section>

                <section class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
                    <div class="p-6 space-y-4">
                        <h2 class="text-base font-semibold text-slate-900">Penulis</h2>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Penulis 1 <span class="text-rose-600">*</span></label>
                            <select name="ketua_id" required class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Pilih penulis 1</option>
                                @foreach($dosens as $d)
                                    <option value="{{ $d->id }}" @selected(old('ketua_id') == $d->id)>{{ $d->nama }} — {{ $d->email }}</option>
                                @endforeach
                            </select>
                            @error('ketua_id') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Penulis lainnya</label>
                            <div id="anggota-wrapper" class="space-y-3">
                                <div class="flex gap-3">
                                    <select name="anggota_id[]" class="flex-1 rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Pilih penulis tambahan (opsional)</option>
                                        @foreach($dosens as $d)
                                            <option value="{{ $d->id }}">{{ $d->nama }} — {{ $d->email }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-outline-danger" onclick="this.closest('.flex').remove()">Hapus</button>
                                </div>
                            </div>
                            <button type="button" id="tambah-anggota" class="mt-3 btn btn-outline-secondary">+ Tambah Penulis</button>
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
                            <button type="button" id="tambah-mahasiswa" class="mt-3 btn btn-outline-secondary">+ Tambah Mahasiswa</button>
                        </div>
                    </div>
                </section>

                <section class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
                    <div class="p-6 space-y-4">
                        <h2 class="text-base font-semibold text-slate-900">Laporan & Jurnal</h2>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Unggah Laporan Jurnal</label>
                            <input type="file" name="laporan_jurnal" accept=".pdf,.doc,.docx" class="block w-full cursor-pointer rounded-lg border border-dashed border-slate-300 bg-slate-50 px-3 py-6 text-sm text-slate-600 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:text-white hover:file:bg-indigo-700">
                            <p class="mt-2 text-xs text-slate-500">Format pdf/doc/docx, maksimum 5MB.</p>
                            @error('laporan_jurnal') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Link Jurnal</label>
                            <input type="url" name="link_jurnal" value="{{ old('link_jurnal') }}" class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500" placeholder="https://contoh-jurnal.com/artikel">
                            @error('link_jurnal') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('dosen.penelitian.index') }}"
                       class="inline-flex items-center gap-2 rounded-full border border-[#2050A0]/20 px-5 py-2 text-sm font-semibold text-[#2050A0] transition hover:bg-[#2050A0]/10">
                        <span class="text-lg">⟲</span>
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-full bg-[#2050A0] px-5 py-2 text-sm font-semibold text-white shadow-md transition hover:bg-[#163B78]">
                        <span class="text-lg">✔</span>
                        Simpan
                    </button>
                </div>
            </form>
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
    </script>
</x-app-layout>
