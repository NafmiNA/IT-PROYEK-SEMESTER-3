@extends('layouts.app')
@section('title','Tambah Pengabdian')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">
  <h1 class="text-xl font-semibold mb-4">Tambah Pengabdian</h1>
  <form action="{{ route('dosen.pengabdian.store') }}" method="POST" class="space-y-4">
    @csrf
    @include('dosen.pengabdian._form')
    <div class="flex gap-2">
      <button class="px-4 py-2 rounded text-white bg-blue-600 hover:bg-blue-700">Simpan</button>
      <a href="{{ route('dosen.pengabdian.index') }}" class="px-4 py-2 rounded border">Batal</a>
    </div>
  </form>
</div>
@endsection
