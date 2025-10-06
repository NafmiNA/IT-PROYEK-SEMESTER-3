<x-app-layout>

    <div class="max-w-5xl mx-auto px-6 py-8">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <p class="text-xs uppercase tracking-wider text-[#2050A0]/70">Perbarui Pengabdian</p>
                <h2 class="text-2xl font-semibold text-[#2050A0]">Edit Pengabdian</h2>
                <p class="text-sm text-gray-500">Perbarui informasi program pengabdian untuk memastikan data tetap akurat.</p>
            </div>
            <a href="{{ route('dosen.pengabdian.show', $pengabdian) }}"
               class="inline-flex items-center gap-2 rounded-full border border-[#2050A0]/20 bg-white px-4 py-2 text-sm font-semibold text-[#2050A0] shadow-sm transition hover:bg-[#2050A0] hover:text-white">
                <span class="text-lg">←</span>
                <span class="hidden sm:inline">Kembali</span>
            </a>
        </div>
        <form action="{{ route('dosen.pengabdian.update', $pengabdian) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <section class="rounded-3xl border-2 border-gray-200 bg-white p-6 ring-1 ring-gray-200/70 shadow-lg">
                <h3 class="text-lg font-semibold text-[#2050A0]">Informasi Umum</h3>
                <div class="mt-4 grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Judul <span class="text-rose-500">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul', $pengabdian->judul) }}" required class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                        @error('judul') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Tahun <span class="text-rose-500">*</span></label>
                        <input type="number" name="tahun" value="{{ old('tahun', $pengabdian->tahun) }}" min="2000" max="2100" required class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                        @error('tahun') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Status</label>
                        <select name="status" class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach(['Draft','Menunggu','Disetujui','Ditolak'] as $status)
                                <option value="{{ $status }}" @selected(old('status', $pengabdian->status) === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                        @error('status') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Bidang</label>
                        <select name="bidang" class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Pilih bidang</option>
                            @foreach(($bidangOptions ?? []) as $option)
                                <option value="{{ $option }}" @selected(old('bidang', $pengabdian->bidang) === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('bidang') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Skema</label>
                        <select name="skema" class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Pilih skema</option>
                            @foreach(($skemaOptions ?? []) as $option)
                                <option value="{{ $option }}" @selected(old('skema', $pengabdian->skema) === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('skema') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Sumber Dana</label>
                        <select name="sumber_dana" class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Pilih sumber dana</option>
                            @foreach(($sumberDanaOptions ?? []) as $option)
                                <option value="{{ $option }}" @selected(old('sumber_dana', $pengabdian->sumber_dana) === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('sumber_dana') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Dana (Rp)</label>
                        <input type="number" name="dana" value="{{ old('dana', $pengabdian->dana) }}" min="0" class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-1 text-xs text-gray-500">Isi angka tanpa pemisah ribuan.</p>
                        @error('dana') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <section class="rounded-3xl border-2 border-gray-200 bg-white p-6 ring-1 ring-gray-200/70 shadow-lg">
                <h3 class="text-lg font-semibold text-[#2050A0]">Tim Pengabdian</h3>
                <div class="mt-4 grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Ketua <span class="text-rose-500">*</span></label>
                        <select name="ketua_id" required class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Pilih ketua</option>
                            @foreach($dosens as $d)
                                <option value="{{ $d->id }}" @selected(old('ketua_id', $pengabdian->ketua?->id) == $d->id)>{{ $d->nama }} — {{ $d->email }}</option>
                            @endforeach
                        </select>
                        @error('ketua_id') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Anggota</label>
                        <select name="anggota_id[]" multiple class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($dosens as $d)
                                <option value="{{ $d->id }}" @selected(in_array($d->id, old('anggota_id', $anggotaTerpilih ?? [])))>{{ $d->nama }} — {{ $d->email }}</option>
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
                    <span class="text-xs font-medium text-gray-400">Unggah dokumentasi terbaru</span>
                </div>
                <input type="file" name="dokumentasi[]" multiple accept="image/*" class="block w-full cursor-pointer rounded-lg border border-dashed border-slate-300 bg-slate-50 px-3 py-8 text-sm text-slate-600 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:text-white hover:file:bg-indigo-700">
                @error('dokumentasi.*') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror

                @if($pengabdian->dokumentasi->isNotEmpty())
                    <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
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
            </section>

            <div class="flex justify-end gap-3">
                <a href="{{ route('dosen.pengabdian.show', $pengabdian) }}" class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 transition hover:bg-gray-100">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-[#2050A0] px-5 py-2 text-sm font-semibold text-white shadow-md transition hover:bg-[#163B78]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
