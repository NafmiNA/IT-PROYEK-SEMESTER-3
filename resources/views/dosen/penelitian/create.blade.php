<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <h2 class="h4 mb-0">Tambah Penelitian</h2>
            <a href="{{ route('dosen.penelitian.index') }}" class="btn btn-link">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50">
        <div class="mx-auto max-w-5xl px-4">
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
                            <input type="text" name="skema" value="{{ old('skema') }}" class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500" placeholder="Dasar / Terapan / Mandiri">
                            @error('skema') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Sumber Dana</label>
                            <input type="text" name="sumber_dana" value="{{ old('sumber_dana') }}" class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500" placeholder="DRPM, Internal, Mandiri">
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
                        <h2 class="text-base font-semibold text-slate-900">Tim Penelitian</h2>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Ketua <span class="text-rose-600">*</span></label>
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
                            <div id="anggota-wrapper" class="space-y-3">
                                <div class="flex gap-3">
                                    <select name="anggota_id[]" class="flex-1 rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Pilih anggota (opsional)</option>
                                        @foreach($dosens as $d)
                                            <option value="{{ $d->id }}">{{ $d->nama }} — {{ $d->email }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-outline-danger" onclick="this.closest('.flex').remove()">Hapus</button>
                                </div>
                            </div>
                            <button type="button" id="tambah-anggota" class="mt-3 btn btn-outline-secondary">+ Tambah Anggota</button>
                        </div>
                    </div>
                </section>

                <section class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
                    <div class="p-6">
                        <h2 class="text-base font-semibold text-slate-900 mb-3">Dokumentasi</h2>
                        <input type="file" name="dokumentasi[]" multiple accept="image/*" class="block w-full cursor-pointer rounded-lg border border-dashed border-slate-300 bg-slate-50 px-3 py-8 text-sm text-slate-600 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:text-white hover:file:bg-indigo-700">
                        <p class="mt-2 text-xs text-slate-500">Boleh unggah beberapa gambar (jpg/jpeg/png). Maks 4MB/berkas.</p>
                        @error('dokumentasi.*') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>
                </section>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('dosen.penelitian.index') }}" class="btn btn-light">Batal</a>
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
    </script>
</x-app-layout>
