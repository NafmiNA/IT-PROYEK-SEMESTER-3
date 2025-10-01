@extends('layouts.app')
@section('title','Detail Pengabdian')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">
  <h1 class="text-xl font-semibold mb-4">{{ $pengabdian->judul }}</h1>
  <div class="space-y-2 text-sm">
    <div><b>Tahun:</b> {{ $pengabdian->tahun }}</div>
    <div><b>Skema:</b> {{ $pengabdian->skema ?? '—' }}</div>
    <div><b>Sumber Dana:</b> {{ $pengabdian->sumber_dana ?? '—' }}</div>
    <div><b>Dana:</b> {{ $pengabdian->dana ? 'Rp '.number_format($pengabdian->dana,0,',','.') : '—' }}</div>
    <div><b>Status:</b> {{ $pengabdian->status }}</div>
    <div><b>Dibuat:</b> {{ $pengabdian->created_at?->format('d M Y H:i') }}</div>
  </div>
  <div class="mt-4">
    <a href="{{ route('dosen.pengabdian.edit',$pengabdian) }}" class="px-3 py-2 border rounded">Edit</a>
    <a href="{{ route('dosen.pengabdian.index') }}" class="px-3 py-2 border rounded">Kembali</a>
  </div>
</div>
@endsection
