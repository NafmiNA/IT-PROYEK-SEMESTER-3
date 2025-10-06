<x-app-layout>
  <x-slot name="header">
    <div class="d-flex align-items-center justify-content-between">
      <h2 class="h4 mb-0">Edit Penelitian</h2>
      <a href="{{ route('dosen.penelitian.index') }}" class="btn btn-link">← Kembali</a>
    </div>
  </x-slot>

  <div class="container py-4">
    <form action="{{ route('dosen.penelitian.update', $penelitian) }}" method="POST" enctype="multipart/form-data" class="card shadow-sm p-4">
      @csrf
      @method('PUT')

      <div class="row g-3">
        <div class="col-md-8">
          <label class="form-label fw-semibold">Judul *</label>
          <input type="text" name="judul" class="form-control" value="{{ old('judul', $penelitian->judul) }}" required>
          @error('judul') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Tahun *</label>
          <input type="number" name="tahun" class="form-control" value="{{ old('tahun', $penelitian->tahun) }}" min="2000" max="2100" required>
          @error('tahun') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Skema</label>
          <input type="text" name="skema" class="form-control" value="{{ old('skema', $penelitian->skema) }}" placeholder="Dasar / Terapan / Mandiri">
          @error('skema') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Sumber Dana</label>
          <input type="text" name="sumber_dana" class="form-control" value="{{ old('sumber_dana', $penelitian->sumber_dana) }}" placeholder="DRPM / Internal / Mandiri">
          @error('sumber_dana') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Dana (Rp)</label>
          <input type="number" name="dana" class="form-control" value="{{ old('dana', $penelitian->dana) }}" min="0">
          <div class="form-text">Isi angka tanpa titik, akan diformat otomatis.</div>
          @error('dana') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Status</label>
          <select name="status" class="form-select">
            @foreach(['Draft','Menunggu','Disetujui','Ditolak'] as $status)
              <option value="{{ $status }}" @selected(old('status', $penelitian->status) === $status)>{{ $status }}</option>
            @endforeach
          </select>
          @error('status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>
      </div>

      <hr class="my-4">

      <h6 class="mb-3">Tim Penelitian</h6>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Ketua *</label>
          <select name="ketua_id" class="form-select" required>
            <option value="">Pilih ketua</option>
            @foreach($dosens as $d)
              <option value="{{ $d->id }}" @selected(old('ketua_id', $penelitian->ketua?->id) == $d->id)>
                {{ $d->nama }} — {{ $d->email }}
              </option>
            @endforeach
          </select>
          @error('ketua_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Anggota</label>
          <select name="anggota_id[]" class="form-select" multiple>
            @foreach($dosens as $d)
              <option value="{{ $d->id }}" @selected(in_array($d->id, old('anggota_id', $anggotaTerpilih ?? [])))>
                {{ $d->nama }} — {{ $d->email }}
              </option>
            @endforeach
          </select>
          <div class="form-text">Tekan Ctrl/Cmd untuk memilih lebih dari satu.</div>
          @error('anggota_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
          @error('anggota_id.*') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>
      </div>

      <hr class="my-4">

      <h6 class="mb-3">Dokumentasi</h6>
      <input type="file" name="dokumentasi[]" class="form-control" accept="image/*" multiple>
      <div class="form-text">Unggah beberapa gambar (maks 4MB per berkas).</div>
      @error('dokumentasi.*') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

      @if($penelitian->dokumentasi->isNotEmpty())
        <div class="mt-3">
          <span class="small text-muted d-block mb-2">Dokumentasi tersimpan:</span>
          <div class="d-flex flex-wrap gap-3">
            @foreach($penelitian->dokumentasi as $doc)
              <div class="border rounded p-2 text-center" style="width:160px">
                <img src="{{ asset('storage/'.$doc->gdrive_path) }}" class="img-fluid rounded mb-2" alt="{{ $doc->file_name }}">
                <div class="small text-truncate" title="{{ $doc->file_name }}">{{ $doc->file_name }}</div>
              </div>
            @endforeach
          </div>
        </div>
      @endif

      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('dosen.penelitian.show', $penelitian) }}" class="btn btn-light">Batal</a>
      </div>
    </form>
  </div>
</x-app-layout>
