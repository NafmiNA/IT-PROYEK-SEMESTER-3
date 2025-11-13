@php $isEdit = isset($pengabdian); @endphp

@if ($errors->any())
  <div class="mb-4 p-3 rounded bg-red-50 text-red-700 border border-red-200">
    <div class="font-semibold mb-1">Periksa kembali:</div>
    <ul class="list-disc pl-5">
      @foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach
    </ul>
  </div>
@endif

<div class="grid md:grid-cols-2 gap-4">
  <div>
    <label class="block text-sm font-medium">Judul *</label>
    <input name="judul" type="text" required
           value="{{ old('judul', $isEdit ? $pengabdian->judul : '') }}"
           class="mt-1 w-full border rounded px-3 py-2">
  </div>

  <div>
    <label class="block text-sm font-medium">Tahun *</label>
    <input name="tahun" type="number" required min="2000" max="{{ date('Y')+1 }}"
           value="{{ old('tahun', $isEdit ? $pengabdian->tahun : date('Y')) }}"
           class="mt-1 w-full border rounded px-3 py-2">
  </div>

  <div>
    <label class="block text-sm font-medium">Skema</label>
    <input name="skema" type="text"
           value="{{ old('skema', $isEdit ? ($pengabdian->skema ?? '') : '') }}"
           class="mt-1 w-full border rounded px-3 py-2" placeholder="Dasar/Terapan/Mandiri">
  </div>

  <div>
    <label class="block text-sm font-medium">Sumber Dana</label>
    <input name="sumber_dana" type="text"
           value="{{ old('sumber_dana', $isEdit ? ($pengabdian->sumber_dana ?? '') : '') }}"
           class="mt-1 w-full border rounded px-3 py-2" placeholder="DRPM/Internal/Mandiri">
  </div>

  <div>
    <label class="block text-sm font-medium">Dana (Rp)</label>
    <input name="dana" id="input-dana" type="text"
           value="{{ old('dana', $isEdit ? ($pengabdian->dana ? number_format($pengabdian->dana,0,',','.') : '') : '') }}"
           class="mt-1 w-full border rounded px-3 py-2" placeholder="15.000.000">
    <p class="text-xs text-gray-500 mt-1">Isi angka, boleh pakai titik. Akan dinormalisasi.</p>
  </div>

  <div>
    <label class="block text-sm font-medium">Status *</label>
    <select name="status" class="mt-1 w-full border rounded px-3 py-2" required>
      @php $st = old('status', $isEdit ? $pengabdian->status : 'Menunggu'); @endphp
      <option value="Menunggu"   {{ $st=='Menunggu'?'selected':'' }}>Menunggu</option>
      <option value="Disetujui"  {{ $st=='Disetujui'?'selected':'' }}>Disetujui</option>
      <option value="Ditolak"    {{ $st=='Ditolak'?'selected':'' }}>Ditolak</option>
    </select>
  </div>
</div>

<script>
  (function(){
    const el=document.getElementById('input-dana'); if(!el) return;
    const toNum=s=>s.replace(/[^\d]/g,''); const fmt=v=>v.replace(/\B(?=(\d{3})+(?!\d))/g,'.');
    el.addEventListener('input',()=>{ const raw=toNum(el.value); el.value=raw?fmt(raw):''; });
  })();
</script>
