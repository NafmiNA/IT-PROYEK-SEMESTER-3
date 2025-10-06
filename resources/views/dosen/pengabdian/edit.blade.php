<x-app-layout>
  <x-slot name="header">
    <div class="d-flex align-items-center justify-content-between">
      <h2 class="h4 mb-0">Edit Pengabdian</h2>
      <a href="{{ route('dosen.pengabdian.index') }}" class="btn btn-link">← Kembali</a>
    </div>
  </x-slot>

  <div class="container py-4">
    <form action="{{ route('dosen.pengabdian.update', $pengabdian->id) }}" method="POST" class="card p-4 shadow-sm">
      @csrf
      @method('PUT')

      {{-- Baris 1: Judul & Tahun --}}
      <div class="row g-3">
        <div class="col-md-8">
          <label class="form-label fw-semibold">Judul *</label>
          <input type="text" name="judul" class="form-control" value="{{ old('judul', $pengabdian->judul) }}" required>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Tahun *</label>
          <input type="number" name="tahun" class="form-control" value="{{ old('tahun', $pengabdian->tahun) }}" required>
        </div>
      </div>

      {{-- Baris 2: Skema & Sumber Dana --}}
      <div class="row g-3 mt-2">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Skema</label>
          <input type="text" name="skema" class="form-control" placeholder="Dasar/Terapan/Mandiri"
                 value="{{ old('skema', $pengabdian->skema) }}">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Sumber Dana</label>
          <input type="text" name="sumber_dana" class="form-control" placeholder="DRPM/Internal/Mandiri"
                 value="{{ old('sumber_dana', $pengabdian->sumber_dana) }}">
        </div>
      </div>

      {{-- Baris 3: Dana & Status --}}
      <div class="row g-3 mt-2">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Dana (Rp)</label>
          <input type="number" name="dana" class="form-control" placeholder="Masukkan jumlah dana"
                 value="{{ old('dana', $pengabdian->dana) }}">
          <div class="form-text">Isi angka tanpa titik. Akan diformat otomatis.</div>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Status *</label>
          <select name="status" class="form-select" required>
            <option value="Menunggu" {{ old('status', $pengabdian->status) == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
            <option value="Disetujui" {{ old('status', $pengabdian->status) == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
            <option value="Ditolak" {{ old('status', $pengabdian->status) == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
          </select>
        </div>
      </div>

      {{-- Tombol --}}
      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4">Update</button>
        <a href="{{ route('dosen.pengabdian.index') }}" class="btn btn-light border">Batal</a>
      </div>
    </form>
  </div>
</x-app-layout>
