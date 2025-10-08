<x-app-layout backUrl="{{ route('dosen.penelitian.index') }}">
    @php
        $anggota = $penelitian->dosens->where('pivot.peran', 'Anggota');
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div class="space-y-1">
                <p class="text-xs uppercase tracking-wider text-[#2050A0]/70">Detil Penelitian</p>
                <h1 class="text-2xl font-semibold text-[#2050A0]">{{ $penelitian->judul }}</h1>
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <span>{{ $penelitian->created_at?->format('d M Y') ?? '-' }}</span>
                    <span class="text-gray-300">•</span>
                    <span>Ketua: {{ $penelitian->ketua->nama ?? 'Tidak diketahui' }}</span>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('dosen.penelitian.edit', $penelitian) }}" class="inline-flex items-center gap-2 rounded-full border border-amber-300 px-4 py-2 text-sm font-semibold text-amber-600 transition hover:bg-amber-500 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.862 4.487z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 7.125L16.875 4.5" />
                    </svg>
                    Edit Data
                </a>
                <a href="{{ route('dosen.penelitian.index') }}" class="inline-flex items-center gap-2 rounded-full border border-[#2050A0]/20 bg-white px-4 py-2 text-sm font-semibold text-[#2050A0] shadow-sm transition hover:bg-[#2050A0] hover:text-white">
                    <span class="text-lg">←</span>
                    <span class="hidden sm:inline">Kembali</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6 py-4">
        <a href="{{ route('dosen.penelitian.index') }}"
           class="inline-flex items-center gap-2 rounded-full border border-[#2050A0]/20 bg-white px-4 py-2 text-sm font-semibold text-[#2050A0] shadow-sm transition hover:bg-[#2050A0] hover:text-white">
            <span class="text-lg">←</span>
            <span class="hidden sm:inline">Kembali ke Kelola Penelitian</span>
        </a>
    </div>

    <div class="max-w-7xl mx-auto px-6 pb-8 space-y-6">
        <section class="grid gap-6 md:grid-cols-2">
            <article class="space-y-4 rounded-3xl border-2 border-gray-200 bg-white p-6 ring-1 ring-gray-200/70 shadow-lg">
                <h2 class="text-lg font-semibold text-[#2050A0]">Informasi Utama</h2>
                <dl class="grid gap-3 text-sm text-gray-600">
                    <div class="flex justify-between border-b border-dashed border-gray-100 pb-2">
                        <dt class="font-medium text-gray-500">Tahun</dt>
                        <dd class="font-semibold text-gray-800">{{ $penelitian->tahun }}</dd>
                    </div>
                    <div class="flex justify-between border-b border-dashed border-gray-100 pb-2">
                        <dt class="font-medium text-gray-500">Skema</dt>
                        <dd class="font-semibold text-gray-800">{{ $penelitian->skema ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between border-b border-dashed border-gray-100 pb-2">
                        <dt class="font-medium text-gray-500">Sumber Dana</dt>
                        <dd class="font-semibold text-gray-800">{{ $penelitian->sumber_dana ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between border-b border-dashed border-gray-100 pb-2">
                        <dt class="font-medium text-gray-500">Total Dana</dt>
                        <dd class="font-semibold text-gray-800">
                            {{ $penelitian->dana ? 'Rp '.number_format($penelitian->dana, 0, ',', '.') : '—' }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-500">Ketua Penelitian</dt>
                        <dd class="font-semibold text-gray-800">{{ $penelitian->ketua->nama ?? '—' }}</dd>
                    </div>
                </dl>
            </article>

            <article class="space-y-4 rounded-3xl border-2 border-gray-200 bg-white p-6 ring-1 ring-gray-200/70 shadow-lg">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-[#2050A0]">Tim Penelitian</h2>
                    <span class="rounded-full bg-[#2050A0]/10 px-3 py-1 text-xs font-semibold text-[#2050A0]">{{ $anggota->count() }} Anggota</span>
                </div>

                <div class="rounded-2xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs uppercase tracking-wide text-gray-400">Ketua</p>
                    <div class="mt-1 flex items-center justify-between text-sm text-gray-700">
                        <span class="font-semibold">{{ $penelitian->ketua->nama ?? '—' }}</span>
                        <span class="text-gray-500">{{ $penelitian->ketua->email ?? '-' }}</span>
                    </div>
                </div>

                @if ($anggota->count())
                    <div class="space-y-3">
                        @foreach ($anggota as $anggotaPenelitian)
                            <div class="rounded-2xl border border-gray-100 bg-white px-4 py-3 shadow-sm">
                                <p class="text-xs uppercase tracking-wide text-gray-400">Anggota</p>
                                <div class="mt-1 flex items-center justify-between text-sm text-gray-700">
                                    <span class="font-semibold">{{ $anggotaPenelitian->nama }}</span>
                                    <span class="text-gray-500">{{ $anggotaPenelitian->email }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 px-4 py-6 text-center text-sm text-gray-500">
                        Belum ada anggota terdaftar.
                    </p>
                @endif

                <div class="mt-4">
                    <p class="text-xs uppercase tracking-wide text-gray-400">Mahasiswa Pendukung</p>
                    @php($pendukung = $penelitian->relationLoaded('mahasiswas') ? $penelitian->mahasiswas : collect())
                    @if($pendukung->count())
                        <div class="mt-2 space-y-2">
                            @foreach($pendukung as $m)
                                <div class="flex items-center justify-between rounded-xl border border-gray-100 bg-white px-4 py-2 text-sm shadow-sm">
                                    <span class="font-semibold">{{ $m->nama }}</span>
                                    <span class="text-gray-500">{{ $m->email }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="rounded-xl border border-dashed border-gray-200 bg-gray-50 px-4 py-3 text-center text-sm text-gray-500">Tidak ada mahasiswa pendukung.</p>
                    @endif
                </div>
            </article>
        </section>

        <section class="rounded-3xl border-2 border-gray-200 bg-white p-6 ring-1 ring-gray-200/70 shadow-lg">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-[#2050A0]">Dokumentasi</h2>
                <span class="text-xs font-medium text-gray-400">{{ $penelitian->dokumentasi->count() }} berkas</span>
            </div>

            @if ($penelitian->dokumentasi->count())
                <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($penelitian->dokumentasi as $dok)
                        <div class="group overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                            <div class="relative aspect-video overflow-hidden bg-gray-100">
                                <img src="{{ asset('storage/'.$dok->gdrive_path) }}" alt="{{ $dok->file_name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            </div>
                            <div class="px-4 py-3 text-sm">
                                <p class="font-semibold text-gray-800 truncate" title="{{ $dok->file_name }}">{{ $dok->file_name }}</p>
                                <p class="text-xs text-gray-500">{{ number_format(($dok->size ?? 0) / 1024, 0) }} KB • {{ $dok->mime ?? 'image/jpeg' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="mt-6 rounded-2xl border border-dashed border-gray-200 bg-gray-50 px-6 py-12 text-center text-sm text-gray-500">
                    Belum ada dokumentasi diunggah.
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
