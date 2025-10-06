@extends('layouts.app') {{-- atau layoutmu sendiri yang sudah memuat Tailwind --}}
@section('title','Tambah Penelitian')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8">
  <h1 class="text-2xl font-semibold text-slate-900">Tambah Penelitian</h1>

  <form
    method="POST"
    action="{{ route('dosen.penelitian.store') }}"
    enctype="multipart/form-data"
    class="mt-6 space-y-8"
  >
    @csrf

    {{-- CARD UTAMA: Info Penelitian --}}
    <section class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
      <div class="p-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
          {{-- Judul --}}
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-slate-700">Judul <span class="text-rose-600">*</span></label>
            <input
              type="text" name="judul" value="{{ old('judul') }}"
              class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm
                     ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500"
              placeholder="Masukkan judul penelitian"
              required
            />
            <p class="mt-2 text-xs text-slate-500">Tulis singkat, jelas, dan spesifik.</p>
            @error('judul') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
          </div>

          {{-- Tahun --}}
          <div>
            <label class="block text-sm font-medium text-slate-700">Tahun <span class="text-rose-600">*</span></label>
            <input
              type="number" name="tahun" value="{{ old('tahun', now()->year) }}"
              class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm
                     ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500"
              min="2000" max="2100" required
            />
            @error('tahun') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
          </div>

          {{-- Skema --}}
          <div>
            <label class="block text-sm font-medium text-slate-700">Skema</label>
            <input
              type="text" name="skema" value="{{ old('skema') }}"
              class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm
                     ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500"
              placeholder="Dasar / Terapan / Mandiri"
            />
            @error('skema') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
          </div>

          {{-- Sumber Dana --}}
          <div>
            <label class="block text-sm font-medium text-slate-700">Sumber Dana</label>
            <input
              type="text" name="sumber_dana" value="{{ old('sumber_dana') }}"
              class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm
                     ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500"
              placeholder="DRPM, Internal, Mandiri"
            />
            @error('sumber_dana') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
          </div>

          {{-- Dana --}}
          <div>
            <label class="block text-sm font-medium text-slate-700">Dana (Rp)</label>
            <input
              type="text" name="dana" value="{{ old('dana') }}"
              class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm
                     ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500"
              placeholder="15000000"
            />
            <p class="mt-2 text-xs text-slate-500">Isi angka tanpa titik/koma (format akan diolah di backend).</p>
            @error('dana') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
          </div>

          {{-- Tempat Terbit --}}
          <div>
            <label class="block text-sm font-medium text-slate-700">Tempat Terbit Jurnal</label>
            <input
              type="text" name="tempat_terbit" value="{{ old('tempat_terbit') }}"
              class="mt-2 w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm
                     ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500"
              placeholder="Nama jurnal atau prosiding"
            />
            @error('tempat_terbit') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
          </div>
        </div>
      </div>
    </section>

    {{-- CARD: Tim Penelitian --}}
<section class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
  <div class="border-b border-slate-200 px-6 py-4">
    <h2 class="text-base font-semibold text-slate-900">Tim Penelitian</h2>
  </div>

  <div class="p-6 space-y-6">
    {{-- === KETUA === --}}
    <div>
      <div class="flex items-center gap-3 mb-3">
        <span class="text-[11px] font-semibold tracking-wider text-slate-600">KETUA</span>
        <div class="h-px bg-slate-200 flex-1"></div>
      </div>

      <label class="sr-only" for="ketua_id">Ketua</label>
      <select id="ketua_id" name="ketua_id" required
        class="w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm
               ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">Pilih ketua</option>
        @foreach($dosens as $d)
          <option value="{{ $d->id }}" @selected(old('ketua_id')==$d->id)>{{ $d->nama }} — {{ $d->email }}</option>
        @endforeach
      </select>
      @error('ketua_id') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
    </div>

    {{-- garis pemisah tegas --}}
    <div class="relative my-2">
      <div class="border-t border-dashed border-slate-200"></div>
      <span class="absolute -top-2 left-0 bg-white px-2 text-[11px] font-semibold tracking-wider text-slate-600">
        ANGGOTA
      </span>
    </div>

    {{-- === ANGGOTA === --}}
    <div id="anggota-section" class="space-y-3">
      <div class="flex gap-3">
        <label class="sr-only">Anggota</label>
        <select name="anggota_id[]"
          class="min-w-0 flex-1 rounded-lg border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm
                 ring-1 ring-inset ring-transparent focus:border-indigo-500 focus:ring-indigo-500">
          <option value="">Pilih anggota (opsional)</option>
          @foreach($dosens as $d)
            <option value="{{ $d->id }}">{{ $d->nama }} — {{ $d->email }}</option>
          @endforeach
        </select>

        <button type="button"
          class="inline-flex items-center rounded-lg bg-rose-50 px-3 py-2 text-sm font-medium text-rose-700
                 ring-1 ring-inset ring-rose-200 hover:bg-rose-100"
          onclick="this.closest('.flex').remove()">
          Hapus
        </button>
      </div>
    </div>

    <button type="button" id="tambah-anggota"
      class="inline-flex items-center rounded-lg bg-indigo-50 px-3 py-2 text-sm font-medium text-indigo-700
             ring-1 ring-inset ring-indigo-200 hover:bg-indigo-100">
      + Tambah Anggota
    </button>
  </div>
</section>

<script>
  document.getElementById('tambah-anggota')?.addEventListener('click', () => {
    const wrapper = document.getElementById('anggota-section');
    const row = wrapper.firstElementChild.cloneNode(true);
    row.querySelector('select').value = '';
    wrapper.appendChild(row);
  });
</script>


    {{-- CARD: Dokumentasi --}}
    <section class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
      <div class="border-b border-slate-200 px-6 py-4">
        <h2 class="text-base font-semibold text-slate-900">Dokumentasi</h2>
      </div>
      <div class="p-6">
        <input
          type="file" name="dokumentasi[]" multiple accept="image/*"
          class="block w-full cursor-pointer rounded-lg border border-dashed border-slate-300 bg-slate-50 px-3 py-8
                 text-sm text-slate-600 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-600 file:px-4 file:py-2
                 file:text-white hover:file:bg-indigo-700"
        />
        <p class="mt-2 text-xs text-slate-500">
          Boleh unggah beberapa gambar (jpg/jpeg/png). Maks 4MB/berkas.
        </p>
        @error('dokumentasi.*') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
      </div>
    </section>

    {{-- Aksi --}}
    <div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary">💾 Simpan</button>
    <a href="{{ route('dosen.penelitian.index') }}" class="btn btn-light">Batal</a>
</div>


    </div>
  </form>
</div>

{{-- JS kecil untuk tambah baris anggota (struktur tetap) --}}
<script>
  const btnAdd = document.getElementById('tambah-anggota');
  const wrapper = document.getElementById('anggota-wrapper');

  btnAdd?.addEventListener('click', () => {
    const row = wrapper.firstElementChild.cloneNode(true);
    // reset value select
    row.querySelector('select').value = '';
    wrapper.appendChild(row);
  });
</script>
@endsection
