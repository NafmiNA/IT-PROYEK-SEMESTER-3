<x-app-layout>

    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs uppercase tracking-wider text-[#2050A0]/70">Manajemen Pengabdian</p>
                <h2 class="text-2xl font-semibold text-[#2050A0]">Kelola Pengabdian</h2>
                <p class="text-sm text-gray-500">Dokumentasikan seluruh aktivitas pengabdian masyarakat dengan rapi.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('dosen.dashboard') }}"
                   class="inline-flex items-center gap-2 rounded-full border border-[#2050A0]/20 bg-white px-4 py-2 text-sm font-semibold text-[#2050A0] shadow-sm transition hover:bg-[#2050A0] hover:text-white">
                    <span class="text-lg">←</span>
                    <span class="hidden sm:inline">Kembali</span>
                </a>
                <a href="{{ route('dosen.pengabdian.create') }}"
                   class="inline-flex items-center gap-2 rounded-full bg-[#2050A0] px-5 py-2 text-sm font-semibold text-white shadow-md transition hover:bg-[#163B78]">
                    <span class="grid h-6 w-6 place-content-center rounded-full bg-white/15 text-lg">+</span>
                    Tambah Pengabdian
                </a>
            </div>
        </div>
        @if (session('success'))
            <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-sm">
                <span class="mt-0.5 text-lg">✅</span>
                <div>
                    <p class="font-semibold">Berhasil</p>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <section class="overflow-hidden rounded-3xl border-2 border-gray-200 bg-white ring-1 ring-gray-200/70 shadow-lg">
            @php
                $totalPengabdian = method_exists($pengabdian, 'total') ? $pengabdian->total() : $pengabdian->count();
            @endphp
            <header class="flex items-center justify-between border-b border-gray-100 bg-gradient-to-r from-[#2050A0]/10 to-[#2050A0]/5 px-6 py-4">
                <div>
                    <h3 class="text-lg font-semibold text-[#2050A0]">Daftar Pengabdian</h3>
                    <p class="text-xs text-gray-500">Total {{ $totalPengabdian }} pengabdian tercatat</p>
                </div>
            </header>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm text-gray-700">
                    <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-6 py-3 text-left">Judul</th>
                            <th class="px-6 py-3 text-left">Tahun</th>
                            <th class="px-6 py-3 text-left">Skema</th>
                            <th class="px-6 py-3 text-left">Sumber Dana</th>
                            <th class="px-6 py-3 text-left">Dana</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($pengabdian as $item)
                            <tr class="transition hover:bg-[#2050A0]/5">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">{{ $item->judul }}</div>
                                    <div class="text-xs text-gray-500">Diupdate {{ $item->updated_at?->diffForHumans() ?? $item->created_at?->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-700">{{ $item->tahun }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $item->skema ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $item->sumber_dana ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-700">
                                    {{ $item->dana ? 'Rp '.number_format($item->dana, 0, ',', '.') : '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusMap = [
                                            'Menunggu'  => 'bg-amber-100 text-amber-700 ring-1 ring-amber-200',
                                            'Disetujui' => 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200',
                                            'Ditolak'   => 'bg-rose-100 text-rose-700 ring-1 ring-rose-200',
                                            'Draft'     => 'bg-slate-200 text-slate-700 ring-1 ring-slate-300',
                                        ];
                                        $badgeClass = $statusMap[$item->status] ?? 'bg-slate-200 text-slate-700 ring-1 ring-slate-300';
                                    @endphp
                                    <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                        <span class="h-2 w-2 rounded-full bg-current opacity-60"></span>
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2 text-xs font-semibold">
                                        <a href="{{ route('dosen.pengabdian.show', $item) }}" class="inline-flex items-center gap-2 rounded-full border border-[#2050A0]/30 px-3 py-1 text-[#2050A0] transition hover:bg-[#2050A0] hover:text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1 1 0 010-.644C3.423 7.51 7.36 4.5 12 4.5s8.577 3.01 9.964 7.178a1 1 0 010 .644C20.577 16.49 16.64 19.5 12 19.5s-8.577-3.01-9.964-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Detail
                                        </a>
                                        <a href="{{ route('dosen.pengabdian.edit', $item) }}" class="inline-flex items-center gap-2 rounded-full border border-amber-300 px-3 py-1 text-amber-600 transition hover:bg-amber-500 hover:text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.862 4.487z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 7.125L16.875 4.5" />
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('dosen.pengabdian.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus pengabdian ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-rose-300 px-3 py-1 text-rose-600 transition hover:bg-rose-500 hover:text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 7.5l.867 12.14A2.25 2.25 0 009.11 21.75h5.78a2.25 2.25 0 002.243-2.11L18 7.5M9.75 10.5v6.75M14.25 10.5v6.75M5.25 7.5h13.5M9 4.5h6a1.5 1.5 0 011.5 1.5V7.5H7.5V6a1.5 1.5 0 011.5-1.5z" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                                    Belum ada data pengabdian. Mulai dengan menambahkan pengabdian pertama Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($pengabdian,'links'))
                <footer class="flex items-center justify-between border-t border-gray-100 bg-gray-50 px-6 py-3 text-xs text-gray-500">
                    <span>Menampilkan {{ $pengabdian->firstItem() ?? 0 }}—{{ $pengabdian->lastItem() ?? 0 }} dari {{ $pengabdian->total() ?? $pengabdian->count() }} entri</span>
                    {{ $pengabdian->links() }}
                </footer>
            @endif
        </section>
    </div>
</x-app-layout>
