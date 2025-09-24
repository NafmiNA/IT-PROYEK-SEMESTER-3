@extends('layouts.app')

@section('title','Dashboard Dosen')

@section('content')
<div class="max-w-7xl mx-auto p-6">

  <h1 class="text-2xl font-bold mb-6">Halo, {{ $dosen->nama }}</h1>

  {{-- Kartu Ringkasan --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="p-5 rounded-2xl shadow bg-white">
      <div class="text-sm text-gray-500">Total Penelitian</div>
      <div class="text-3xl font-semibold">{{ $totalPenelitian }}</div>
    </div>
    <div class="p-5 rounded-2xl shadow bg-white">
      <div class="text-sm text-gray-500">Total Pengabdian</div>
      <div class="text-3xl font-semibold">{{ $totalPengabdian }}</div>
    </div>
    <div class="p-5 rounded-2xl shadow bg-white">
      <div class="text-sm text-gray-500">Dokumentasi</div>
      <div class="text-3xl font-semibold">{{ $totalDokumentasi }}</div>
    </div>
    <div class="p-5 rounded-2xl shadow bg-white">
      <div class="text-sm text-gray-500">Menunggu Verifikasi</div>
      <div class="text-3xl font-semibold">{{ $menungguVerif }}</div>
    </div>
  </div>

  {{-- Tabel Ringkas --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow">
      <div class="px-5 py-3 border-b font-semibold">Penelitian Terbaru</div>
      <div class="p-5">
        <ul class="space-y-3">
          @forelse($recentPenelitian as $p)
            <li class="flex items-center justify-between">
              <div>
                <div class="font-medium">{{ $p->judul }}</div>
                <div class="text-xs text-gray-500">Tahun {{ $p->tahun }} • Status: {{ $p->status }}</div>
              </div>
              <a href="#" class="text-blue-600 text-sm">Detail</a>
            </li>
          @empty
            <li class="text-gray-500 text-sm">Belum ada data.</li>
          @endforelse
        </ul>
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow">
      <div class="px-5 py-3 border-b font-semibold">Pengabdian Terbaru</div>
      <div class="p-5">
        <ul class="space-y-3">
          @forelse($recentPengabdian as $g)
            <li class="flex items-center justify-between">
              <div>
                <div class="font-medium">{{ $g->judul }}</div>
                <div class="text-xs text-gray-500">Tahun {{ $g->tahun }} • Status: {{ $g->status }}</div>
              </div>
              <a href="#" class="text-blue-600 text-sm">Detail</a>
            </li>
          @empty
            <li class="text-gray-500 text-sm">Belum ada data.</li>
          @endforelse
        </ul>
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow lg:col-span-2">
      <div class="px-5 py-3 border-b font-semibold">Riwayat Verifikasi</div>
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
                  <span class="px-2 py-1 rounded-full text-xs
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
              <tr><td class="py-4 text-gray-500" colspan="4">Belum ada verifikasi.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>
@endsection
