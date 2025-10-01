@extends('layouts.app')

@section('title','Dashboard Dosen')

@section('content')
<div class="max-w-7xl mx-auto p-6">

  <h1 class="text-2xl font-bold mb-6">Halo, {{ $dosen->nama }}</h1>

  {{-- Kartu Ringkasan --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="p-6 rounded-2xl shadow bg-white hover:shadow-lg transition">
      <p class="text-sm text-gray-500">Total Penelitian</p>
      <p class="text-3xl font-bold text-blue-600">{{ $totalPenelitian }}</p>
    </div>
    <div class="p-6 rounded-2xl shadow bg-white hover:shadow-lg transition">
      <p class="text-sm text-gray-500">Total Pengabdian</p>
      <p class="text-3xl font-bold text-green-600">{{ $totalPengabdian }}</p>
    </div>
    <div class="p-6 rounded-2xl shadow bg-white hover:shadow-lg transition">
      <p class="text-sm text-gray-500">Dokumentasi</p>
      <p class="text-3xl font-bold text-purple-600">{{ $totalDokumentasi }}</p>
    </div>
    <div class="p-6 rounded-2xl shadow bg-white hover:shadow-lg transition">
      <p class="text-sm text-gray-500">Menunggu Verifikasi</p>
      <p class="text-3xl font-bold text-yellow-600">{{ $menungguVerif }}</p>
    </div>
  </div>

  {{-- Tabel Ringkas --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Penelitian --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden">
      <div class="px-5 py-3 border-b font-semibold text-lg">Penelitian Terbaru</div>
      <div class="p-5">
        <ul class="space-y-3">
          @forelse($recentPenelitian as $p)
            <li class="flex items-center justify-between border-b last:border-0 pb-2">
              <div>
                <p class="font-medium">{{ $p->judul }}</p>
                <p class="text-xs text-gray-500">Tahun {{ $p->tahun }} • Status: {{ $p->status }}</p>
              </div>
              <a href="#" class="text-blue-600 text-sm hover:underline">Detail</a>
            </li>
          @empty
            <li class="text-gray-500 text-sm">Belum ada data.</li>
          @endforelse
        </ul>
      </div>
    </div>

    {{-- Pengabdian --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden">
      <div class="px-5 py-3 border-b font-semibold text-lg">Pengabdian Terbaru</div>
      <div class="p-5">
        <ul class="space-y-3">
          @forelse($recentPengabdian as $g)
            <li class="flex items-center justify-between border-b last:border-0 pb-2">
              <div>
                <p class="font-medium">{{ $g->judul }}</p>
                <p class="text-xs text-gray-500">Tahun {{ $g->tahun }} • Status: {{ $g->status }}</p>
              </div>
              <a href="#" class="text-blue-600 text-sm hover:underline">Detail</a>
            </li>
          @empty
            <li class="text-gray-500 text-sm">Belum ada data.</li>
          @endforelse
        </ul>
      </div>
    </div>

    {{-- Verifikasi --}}
    <div class="bg-white rounded-2xl shadow lg:col-span-2 overflow-hidden">
      <div class="px-5 py-3 border-b font-semibold text-lg">Riwayat Verifikasi</div>
      <div class="p-5 overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead>
            <tr class="text-left text-gray-500">
              <th class="py-2">Tanggal</th>
              <th class="py-2">Obyek</th>
              <th class="py-2">Status</th>
              <th class="py-2">Catatan</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentVerif as $v)
              <tr class="border-t">
                <td class="py-2">{{ $v->tanggal?->format('Y-m-d') }}</td>
                <td class="py-2">
                  @if($v->penelitian_id) Penelitian #{{ $v->penelitian_id }}
                  @elseif($v->pengabdian_id) Pengabdian #{{ $v->pengabdian_id }}
                  @endif
                </td>
                <td class="py-2">
                  <span class="px-2 py-1 rounded-full text-xs font-medium
                    @class([
                      'bg-yellow-100 text-yellow-800' => $v->status==='Menunggu',
                      'bg-green-100 text-green-700' => $v->status==='Disetujui',
                      'bg-red-100 text-red-700' => $v->status==='Ditolak',
                    ])">
                    {{ $v->status }}
                  </span>
                </td>
                <td class="py-2">{{ $v->catatan ?? '-' }}</td>
              </tr>
            @empty
              <tr>
                <td class="py-4 text-gray-500 text-center" colspan="4">Belum ada verifikasi.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>
@endsection
