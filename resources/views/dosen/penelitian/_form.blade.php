@php $isEdit = isset($penelitian); @endphp

{{-- Alert error validasi --}}
@if ($errors->any())
  <div class="alert alert-danger">
    <div class="fw-semibold mb-1">Periksa kembali isian berikut:</div>
    <ul class="mb-0 ps-3">
      @foreach ($errors->all() as $err)
        <li>{{ $err }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="row g-3">
  {{-- Judul --}}
  <div class="col-12">
    <label class="form-label">Judul <span class="text-danger">*</span></label>
    <input type="text" name="judul"
           value="{{ old('judul', $isEdit ? $penelitian->judul : '') }}"
           class="form-control @error('judul') is-invalid @enderror"
           placeholder="Masukkan judul penelitian" required>
    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <div class="form-text">Tulis singkat, jelas, dan spesifik.</div>
  </div>

  {{-- Tahun --}}
  <div class="col-md-6">
    <label class="form-label">Tahun <span class="text-danger">*</span></label>
    <input type="number" name="tahun" min="2000" max="{{ date('Y')+1 }}"
           value="{{ old('tahun', $isEdit ? $penelitian->tahun : date('Y')) }}"
           class="form-control @error('tahun') is-invalid @enderror" required>
    @error('tahun') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  {{-- Skema --}}
  <div class="col-md-6">
    <label class="form-label">Skema</label>
    <input type="text" name="skema"
           value="{{ old('skema', $isEdit ? ($penelitian->skema ?? '') : '') }}"
           class="form-control" placeholder="Dasar / Terapan / Mandiri">
  </div>

  {{-- Sumber Dana --}}
  <div class="col-md-6">
    <label class="form-label">Sumber Dana</label>
    <input type="text" name="sumber_dana"
           value="{{ old('sumber_dana', $isEdit ? ($penelitian->sumber_dana ?? '') : '') }}"
           class="form-control" placeholder="DRPM, Internal, Mandiri">
  </div>

  {{-- Dana (Rp) --}}
  <div class="col-md-6">
    <label class="form-label">Dana (Rp)</label>
    <div class="input-group">
      <span class="input-group-text">Rp</span>
      <input type="text" inputmode="numeric" id="input-dana" name="dana"
             value="{{ old('dana', $isEdit ? ($penelitian->dana ?? '') : '') }}"
             class="form-control" placeholder="15.000.000">
    </div>
    <div class="form-text">Isi angka, otomatis diberi pemisah ribuan.</div>
  </div>

  {{-- Tempat Terbit Jurnal --}}
  {{-- Tempat Terbit Jurnal --}}
<div class="col-md-6">
  <label class="form-label">Tempat Terbit Jurnal</label>
  <input type="text" name="tempat_terbit"
         value="{{ old('tempat_terbit', $isEdit ? ($penelitian->tempat_terbit ?? '') : '') }}"
         class="form-control @error('tempat_terbit') is-invalid @enderror"
         placeholder="Masukkan nama jurnal atau prosiding">
  @error('tempat_terbit') 
    <div class="invalid-feedback">{{ $message }}</div> 
  @enderror
  <div class="form-text">Tuliskan nama jurnal, prosiding, atau penerbit penelitian.</div>
</div>


  {{-- Status --}}
  <div class="col-md-6">
    <label class="form-label">Status <span class="text-danger">*</span></label>
    <select name="status" class="form-select" required>
        <option value="Menunggu" {{ old('status', $isEdit ? $penelitian->status : 'Menunggu')=='Menunggu'?'selected':'' }}>Menunggu</option>
        <option value="Disetujui" {{ old('status', $isEdit ? $penelitian->status : '')=='Disetujui'?'selected':'' }}>Disetujui</option>
        <option value="Ditolak" {{ old('status', $isEdit ? $penelitian->status : '')=='Ditolak'?'selected':'' }}>Ditolak</option>
    </select>
    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <div class="form-text">Status otomatis <b>Menunggu</b> ketika diajukan verifikasi.</div>
  </div>
</div>

{{-- Formatter angka Rp (titik ribuan) --}}
<script>
  (function(){
    const el = document.getElementById('input-dana');
    if(!el) return;
    const toNumber = s => s.replace(/[^\d]/g,'');
    const format = v => v.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    el.addEventListener('input', () => {
      const raw = toNumber(el.value);
      el.value = raw ? format(raw) : '';
    });
  })();
</script>
