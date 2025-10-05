<x-app-layout>
  <x-slot name="header">
    <div class="d-flex align-items-center justify-content-between">
      <h2 class="h5 mb-0">Edit Penelitian</h2>
      <a href="{{ route('dosen.penelitian.index') }}" class="link-secondary">← Kembali</a>
    </div>
  </x-slot>

  <div class="container py-4">
    <form action="{{ route('dosen.penelitian.update', $penelitian) }}" method="POST" class="mx-auto" style="max-width: 760px;">
      @csrf @method('PUT')

      <div class="card shadow-sm">
        <div class="card-body">
          @include('dosen.penelitian._form', ['penelitian' => $penelitian])
        </div>
      </div>

      <div class="d-flex gap-2 mt-3">
        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('dosen.penelitian.index') }}" class="btn btn-outline-secondary">Batal</a>
      </div>
    </form>
  </div>
</x-app-layout>