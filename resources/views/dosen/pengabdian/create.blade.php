<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs uppercase tracking-wider text-[#2050A0]/70">Manajemen Pengabdian</p>
                <h2 class="text-2xl font-semibold text-[#2050A0]">Tambah Pengabdian</h2>
                <p class="text-sm text-gray-500">Daftarkan kegiatan pengabdian terbaru beserta tim dan dokumentasinya.</p>
            </div>
            <a href="{{ route('dosen.pengabdian.index') }}" class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 transition hover:bg-gray-100">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto px-6 py-8">
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
                        <select name="status" class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach(['Draft','Menunggu','Disetujui','Ditolak'] as $status)
                                <option value="{{ $status }}" @selected(old('status', 'Menunggu') === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                        @error('status') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Bidang</label>
                        <input type="text" name="bidang" value="{{ old('bidang') }}" class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500" placeholder="Pendidikan, Ekonomi, ...">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Skema</label>
                        <input type="text" name="skema" value="{{ old('skema') }}" class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500" placeholder="Kemitraan, PkM, ...">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Sumber Dana</label>
                        <input type="text" name="sumber_dana" value="{{ old('sumber_dana') }}" class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500" placeholder="DRPM, Internal, ...">
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
                        <label class="block text-sm font-medium text-slate-700">Anggota (opsional)</label>
                        <select name="anggota_id[]" multiple class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($dosens as $d)
                                <option value="{{ $d->id }}" @selected(collect(old('anggota_id', []))->contains($d->id))>{{ $d->nama }} — {{ $d->email }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Tekan Ctrl/Cmd untuk memilih lebih dari satu.</p>
                        @error('anggota_id') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                        @error('anggota_id.*') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <section class="rounded-3xl border-2 border-gray-200 bg-white p-6 ring-1 ring-gray-200/70 shadow-lg space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-[#2050A0]">Dokumentasi</h3>
                    <span class="text-xs font-medium text-gray-400">Unggah dokumentasi pendukung</span>
                </div>
                <input type="file" name="dokumentasi[]" multiple accept="image/*" class="block w-full cursor-pointer rounded-lg border border-dashed border-slate-300 bg-slate-50 px-3 py-8 text-sm text-slate-600 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:text-white hover:file:bg-indigo-700">
                <p class="text-xs text-gray-500">Boleh unggah beberapa gambar (jpg/jpeg/png). Maks 4MB per file.</p>
                @error('dokumentasi.*') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            </section>

            <div class="flex justify-end gap-3">
                <a href="{{ route('dosen.pengabdian.index') }}" class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 transition hover:bg-gray-100">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-[#2050A0] px-5 py-2 text-sm font-semibold text-white shadow-md transition hover:bg-[#163B78]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    Simpan Pengabdian
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
