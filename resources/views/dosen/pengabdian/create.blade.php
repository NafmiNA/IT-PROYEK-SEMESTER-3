{{-- resources/views/dosen/pengabdian/create.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <div class="d-flex align-items-center justify-content-between">
      <h2 class="h4 mb-0">Tambah Pengabdian</h2>
      <a href="{{ route('dosen.pengabdian.index') }}" class="btn btn-link">← Kembali</a>
    </div>
  </x-slot>

  <div class="container py-4">
    <form action="{{ route('dosen.pengabdian.store') }}" method="POST" enctype="multipart/form-data" class="card p-3 shadow-sm">
      @csrf

      {{-- Identitas --}}
      <div class="row g-3">
        <div class="col-md-8">
          <label class="form-label">Judul *</label>
          <input type="text" name="judul" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Tahun *</label>
          <input type="number" name="tahun" class="form-control" value="{{ date('Y') }}" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Bidang</label>
          <input type="text" name="bidang" class="form-control">
        </div>

        {{-- 🟢 Tambahan --}}
        <div class="col-md-6">
          <label class="form-label">Skema</label>
          <input type="text" name="skema" class="form-control">
        </div>

        <div class="col-md-6">
          <label class="form-label">Sumber Dana</label>
          <input type="text" name="sumber_dana" class="form-control">
        </div>

        <div class="col-md-6">
          <label class="form-label">Dana (Rp)</label>
          <input type="number" name="dana" class="form-control">
        </div>
      </div>

      <hr class="my-4">

      {{-- Tim --}}
      <h6 class="mb-3">Tim Pengabdian</h6>
      <div class="row g-3">
        <div class="col-md-12">
          <label class="form-label">Ketua *</label>
          <select name="ketua_id" class="form-select" required>
            <option value="">Pilih ketua</option>
            @foreach($dosens as $d)
              <option value="{{ $d->id }}">{{ $d->nama }} — {{ $d->email }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-12">
          <label class="form-label">Anggota (opsional)</label>
          <select name="anggota_id[]" class="form-select" multiple>
            @foreach($dosens as $d)
              <option value="{{ $d->id }}">{{ $d->nama }} — {{ $d->email }}</option>
            @endforeach
          </select>
          <div class="form-text">Tekan Ctrl/Cmd untuk pilih banyak.</div>
        </div>
      </div>

      <hr class="my-4">

      {{-- Dokumentasi --}}
      <h6 class="mb-3">Dokumentasi</h6>
      <input type="file" name="dokumentasi[]" class="form-control" accept="image/*" multiple>
      <div class="form-text">Boleh banyak file (jpg/jpeg/png), maks 4MB/berkas.</div>

      <div class="mt-4 d-flex gap-2">
        <button class="btn btn-primary">Simpan</button>
        <a class="btn btn-light" href="{{ route('dosen.pengabdian.index') }}">Batal</a>
      </div>
    </form>
  </div>
</x-app-layout>
