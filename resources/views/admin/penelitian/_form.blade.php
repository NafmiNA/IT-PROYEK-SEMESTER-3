<x-app-layout backUrl="{{ route('dosen.penelitian.index') }}">
  <x-slot name="header">
    <h2 class="text-xl font-semibold text-gray-800">Tambah Penelitian</h2>
  </x-slot>

  <div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <form method="POST" action="{{ route('dosen.penelitian.store') }}" enctype="multipart/form-data"
            class="bg-white rounded-2xl border border-gray-200 shadow p-6 space-y-6">
        @csrf

        {{-- Judul --}}
        <div>
          <label class="block text-sm font-medium text-gray-700">Judul <span class="text-rose-600">*</span></label>
          <input name="judul" value="{{ old('judul') }}" required
                 class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                 placeholder="Masukkan judul penelitian">
          <p class="text-xs text-gray-500 mt-1">Tulis singkat, jelas, dan spesifik.</p>
          @error('judul')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          {{-- Tahun --}}
          <div>
            <label class="block text-sm font-medium text-gray-700">Tahun <span class="text-rose-600">*</span></label>
            <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}" required
                   class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('tahun')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
          </div>

          {{-- Skema --}}
          <div>
            <label class="block text-sm font-medium text-gray-700">Skema</label>
            <input name="skema" value="{{ old('skema') }}"
                   class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                   placeholder="Dasar / Terapan / Mandiri">
          </div>

          {{-- Sumber Dana --}}
          <div>
            <label class="block text-sm font-medium text-gray-700">Sumber Dana</label>
            <input name="sumber_dana" value="{{ old('sumber_dana') }}"
                   class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                   placeholder="DRPM, Internal, Mandiri">
          </div>

          {{-- Dana --}}
          <div>
            <label class="block text-sm font-medium text-gray-700">Dana (Rp)</label>
            <input name="dana" value="{{ old('dana') }}"
                   class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                   placeholder="15000000">
            <p class="text-xs text-gray-500 mt-1">Isi angka tanpa titik/koma (format akan diolah di backend).</p>
          </div>

          {{-- Tempat Terbit --}}
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Tempat Terbit Jurnal</label>
            <input name="tempat_terbit" value="{{ old('tempat_terbit') }}"
                   class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                   placeholder="Nama jurnal atau prosiding">
          </div>
        </div>

        {{-- Tim Penelitian (mengganti Status) --}}
        <div class="border-t border-gray-100 pt-4">
          <h3 class="font-semibold text-gray-800 mb-3">Tim Penelitian</h3>

          {{-- Ketua --}}
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Ketua <span class="text-rose-600">*</span></label>
            <select name="ketua_id" required
                    class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
              <option value="">Pilih ketua</option>
              @foreach($dosens as $d)
                <option value="{{ $d->id }}" @selected(old('ketua_id')===$d->id)>
                  {{ $d->nama }} — {{ $d->email }}
                </option>
              @endforeach
            </select>
            @error('ketua_id')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
          </div>

          {{-- Anggota (dinamis) --}}
          <div x-data="{rows: [0]}" class="space-y-3">
            <label class="block text-sm font-medium text-gray-700">Anggota</label>

            <template x-for="(r,idx) in rows" :key="r">
              <div class="flex gap-2">
                <select :name="`anggota_id[${idx}]`"
                        class="flex-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                  <option value="">Pilih anggota</option>
                  @foreach($dosens as $d)
                    <option value="{{ $d->id }}">{{ $d->nama }} — {{ $d->email }}</option>
                  @endforeach
                </select>
                <button type="button" @click="rows.splice(idx,1)"
                        class="px-3 py-2 text-sm rounded-lg bg-red-100 text-red-700 hover:bg-red-200">Hapus</button>
              </div>
            </template>

            <button type="button" @click="rows.push(Date.now())"
                    class="px-3 py-2 text-sm rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">
              + Tambah Anggota
            </button>
          </div>
        </div>

        {{-- Dokumentasi (gambar/foto) --}}
        <div class="border-t border-gray-100 pt-4">
          <h3 class="font-semibold text-gray-800 mb-3">Dokumentasi (Tersimpan ke Google Drive)</h3>
          <input type="file" name="dokumentasi[]" multiple accept="image/*"
                 class="block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
          <p class="text-xs text-gray-500 mt-1">Boleh unggah beberapa gambar (.jpg/.jpeg/.png). Maks 4MB/berkas.</p>
          @error('dokumentasi.*')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-2">
          <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Simpan</button>
          <a href="{{ route('dosen.penelitian.index') }}" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">Batal</a>
        </div>
      </form>
    </div>
  </div>

  {{-- AlpineJS untuk field anggota dinamis (jika belum ada) --}}
  <script src="https://unpkg.com/alpinejs" defer></script>
</x-app-layout>
