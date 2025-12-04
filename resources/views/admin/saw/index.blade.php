<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Data Prestasi Dosen (SAW)
                </h2>
            </div>
            <a href="{{ route('admin.saw.ranking', ['tahun' => $tahun ?? now()->year]) }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-lg transition transform hover:scale-105"
               style="background-color: #16a34a;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="text-lg">🏆 LIHAT RANKING</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif
            
            @if(session('warning'))
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative">
                    {{ session('warning') }}
                </div>
            @endif
            
            <!-- Year Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('admin.saw.index') }}" class="flex items-center gap-4">
                    <label class="font-semibold text-gray-700">Filter Tahun:</label>
                    <select name="tahun" onchange="this.form.submit()" 
                            class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-gray-600">
                        Total: <strong>{{ $prestasi->count() }}</strong> dosen
                    </span>
                </form>
            </div>
            
            <!-- Tombol Hitung Ranking - BESAR & JELAS -->
            <div class="bg-white border-4 border-blue-600 rounded-xl shadow-2xl p-8 text-center">
                <div class="flex flex-col items-center gap-4">
                    <div>
                        <h3 class="text-3xl font-bold mb-2 text-gray-900">HITUNG RANKING DOSEN BERPRESTASI</h3>
                        <p class="text-gray-600 text-lg">Klik tombol di bawah untuk melihat hasil ranking menggunakan metode SAW</p>
                    </div>
                    <a href="{{ route('admin.saw.ranking', ['tahun' => $tahun ?? now()->year]) }}" 
                       class="inline-flex items-center gap-3 px-12 py-5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-2xl rounded-lg shadow-xl transition transform hover:scale-105">
                        LIHAT RANKING
                    </a>
                    <p class="text-gray-600 text-sm">Data dari {{ $prestasi->count() }} dosen siap dihitung</p>
                </div>
            </div>

            <!-- Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-start gap-4">
                    <svg class="w-8 h-8 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-blue-900 mb-2 text-lg">Informasi untuk Admin</h3>
                        <p class="text-sm text-blue-800 mb-2">
                            Halaman ini menampilkan <strong>rekap data prestasi yang diupload oleh para dosen</strong>. 
                            Admin tidak bisa menambah atau mengedit data prestasi secara manual.
                        </p>
                        <p class="text-sm text-blue-800">
                            <strong>Tugas Admin:</strong> Klik tombol kuning di atas untuk menghitung ranking menggunakan metode SAW (Simple Additive Weighting).
                        </p>
                    </div>
                </div>
            </div>

            <!-- Data Rekap -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Rekap Data Prestasi Dosen</h3>

                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Data Prestasi Tahun {{ $tahun }}
                    </h3>
                    
                    @if($prestasi->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-lg font-medium">Belum ada data prestasi untuk tahun {{ $tahun }}</p>
                            <p class="text-sm">Silakan tambahkan data menggunakan form di atas</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Publikasi (K1)</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">SINTA (K2)</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hibah (K3)</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Buku (K4)</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($prestasi as $index => $p)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="font-medium text-gray-900">{{ $p->dosen->nama }}</div>
                                                <div class="text-xs text-gray-500">NIP: {{ $p->dosen->nip }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">{{ $p->publikasi }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">{{ $p->skor_sinta }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">Rp {{ number_format($p->hibah, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">{{ $p->buku }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <div class="text-center text-gray-500 text-sm">
                                                    <span class="inline-flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        Dikelola Dosen
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    

</x-app-layout>
