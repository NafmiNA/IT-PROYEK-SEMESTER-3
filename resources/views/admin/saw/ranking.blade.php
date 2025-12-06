<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Hasil Ranking Prestasi Dosen (SAW Method)
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.saw.export', ['tahun' => $tahun]) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-green-800 hover:bg-green-900 text-white rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export Ranking
                </a>
                <a href="{{ route('admin.saw.index', ['tahun' => $tahun]) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Data
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <a href="{{ route('admin.saw.index', ['tahun' => $tahun]) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Data
            </a>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Year Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('admin.saw.ranking') }}" class="flex items-center gap-4">
                    <label class="font-semibold text-gray-700">Tahun:</label>
                    <select name="tahun" onchange="this.form.submit()" 
                            class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            
            <!-- AHP Weights -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Bobot Kriteria (AHP)</h3>
                <div class="grid grid-cols-4 gap-4">
                    @foreach(['K1' => 'Publikasi', 'K2' => 'Skor SINTA', 'K3' => 'Hibah', 'K4' => 'Buku'] as $kode => $nama)
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="text-sm text-gray-600">{{ $nama }} ({{ $kode }})</div>
                            <div class="text-2xl font-bold text-blue-600">
                                @if(isset($bobot[$kode]))
                                    {{ number_format($bobot[$kode]->bobot, 4) }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Summary Statistics -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Tahun {{ $tahun }}</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-600">Total Dosen</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $summary['total_dosen'] }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-600">Rata-rata Publikasi</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $summary['avg_publikasi'] }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-600">Rata-rata SINTA</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $summary['avg_skor_sinta'] }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-600">Rata-rata Buku</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $summary['avg_buku'] }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Ranking Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Peringkat Dosen Berdasarkan Prestasi
                    </h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Publikasi</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">SINTA</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hibah</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Buku</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Skor Akhir</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($ranking as $item)
                                    <tr class="hover:bg-gray-50 {{ $item['rank'] <= 3 ? 'bg-yellow-50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($item['rank'] == 1)
                                                <span class="inline-flex items-center justify-center w-10 h-10 bg-yellow-400 text-gray-900 rounded-full font-bold text-lg">
                                                    1
                                                </span>
                                            @elseif($item['rank'] == 2)
                                                <span class="inline-flex items-center justify-center w-10 h-10 bg-gray-400 text-white rounded-full font-bold text-lg">
                                                    2
                                                </span>
                                            @elseif($item['rank'] == 3)
                                                <span class="inline-flex items-center justify-center w-10 h-10 bg-orange-400 text-white rounded-full font-bold text-lg">
                                                    3
                                                </span>
                                            @else
                                                <span class="inline-flex items-center justify-center w-10 h-10 bg-gray-200 text-gray-700 rounded-full font-semibold">
                                                    {{ $item['rank'] }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">{{ $item['dosen']->nama }}</div>
                                            <div class="text-xs text-gray-500">NIP: {{ $item['dosen']->nip }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                            {{ $item['raw_data']['publikasi'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                            {{ $item['raw_data']['skor_sinta'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                            Rp {{ number_format($item['raw_data']['hibah'], 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                            {{ $item['raw_data']['buku'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                                {{ $item['rank'] == 1 ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $item['rank'] == 2 ? 'bg-gray-100 text-gray-800' : '' }}
                                                {{ $item['rank'] == 3 ? 'bg-orange-100 text-orange-800' : '' }}
                                                {{ $item['rank'] > 3 ? 'bg-blue-100 text-blue-800' : '' }}">
                                                {{ number_format($item['skor_akhir'], 4) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <button onclick="showDetail({{ json_encode($item) }})"
                                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                                                Lihat Detail
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Detail Modal -->
    <div id="detailModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Detail Perhitungan SAW</h3>
                <button onclick="closeDetailModal()" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                    Tutup
                </button>
            </div>
            
            <div id="detailContent" class="space-y-4">
                <!-- Content will be inserted by JavaScript -->
            </div>
        </div>
    </div>
    
    <script>
        function showDetail(item) {
            const content = `
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <h4 class="font-semibold text-gray-900 mb-2">Dosen: ${item.dosen.nama}</h4>
                    <p class="text-sm text-gray-600">Peringkat: #${item.rank} | Skor Akhir: ${item.skor_akhir.toFixed(4)}</p>
                </div>
                
                <div class="space-y-6">
                    <!-- Langkah 1: Data Asli -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h5 class="font-semibold text-gray-900 mb-3">Langkah 1: Data Asli Dosen</h5>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-white p-3 rounded border">
                                <p class="text-xs text-gray-500">K1 - Publikasi</p>
                                <p class="text-lg font-semibold text-gray-900">${item.raw_data.publikasi}</p>
                            </div>
                            <div class="bg-white p-3 rounded border">
                                <p class="text-xs text-gray-500">K2 - Skor SINTA</p>
                                <p class="text-lg font-semibold text-gray-900">${item.raw_data.skor_sinta}</p>
                            </div>
                            <div class="bg-white p-3 rounded border">
                                <p class="text-xs text-gray-500">K3 - Hibah</p>
                                <p class="text-lg font-semibold text-gray-900">Rp ${item.raw_data.hibah.toLocaleString('id-ID')}</p>
                            </div>
                            <div class="bg-white p-3 rounded border">
                                <p class="text-xs text-gray-500">K4 - Buku</p>
                                <p class="text-lg font-semibold text-gray-900">${item.raw_data.buku}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Langkah 2: Normalisasi -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h5 class="font-semibold text-gray-900 mb-3">Langkah 2: Normalisasi Data (Benefit Criteria)</h5>
                        <p class="text-sm text-gray-600 mb-3">Rumus: r<sub>ij</sub> = x<sub>ij</sub> / max(x<sub>j</sub>)</p>
                        <div class="space-y-2">
                            ${Object.entries(item.detail_perhitungan).map(([kode, detail]) => {
                                const nama = kode === 'K1' ? 'Publikasi' : kode === 'K2' ? 'Skor SINTA' : kode === 'K3' ? 'Hibah' : 'Buku';
                                const nilai = kode === 'K1' ? item.raw_data.publikasi : kode === 'K2' ? item.raw_data.skor_sinta : kode === 'K3' ? item.raw_data.hibah : item.raw_data.buku;
                                return `
                                    <div class="bg-blue-50 p-3 rounded">
                                        <p class="text-sm font-medium text-gray-900">${kode} - ${nama}</p>
                                        <p class="text-sm text-gray-700 mt-1">Normalisasi = ${nilai} / max = <span class="font-semibold text-blue-600">${detail.normalized.toFixed(4)}</span></p>
                                    </div>
                                `;
                            }).join('')}
                        </div>
                    </div>
                    
                    <!-- Langkah 3: Bobot AHP -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h5 class="font-semibold text-gray-900 mb-3">Langkah 3: Bobot Kriteria (dari AHP)</h5>
                        <div class="grid grid-cols-4 gap-2">
                            ${Object.entries(item.detail_perhitungan).map(([kode, detail]) => {
                                const nama = kode === 'K1' ? 'Publikasi' : kode === 'K2' ? 'SINTA' : kode === 'K3' ? 'Hibah' : 'Buku';
                                return `
                                    <div class="bg-green-50 p-2 rounded text-center">
                                        <p class="text-xs text-gray-600">${nama}</p>
                                        <p class="text-lg font-bold text-green-700">${detail.weight.toFixed(4)}</p>
                                    </div>
                                `;
                            }).join('')}
                        </div>
                    </div>
                    
                    <!-- Langkah 4: Perhitungan SAW -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h5 class="font-semibold text-gray-900 mb-3">Langkah 4: Perhitungan SAW (Weighted Sum)</h5>
                        <p class="text-sm text-gray-600 mb-3">Rumus: V<sub>i</sub> = Σ (w<sub>j</sub> × r<sub>ij</sub>)</p>
                        <div class="space-y-2">
                            ${Object.entries(item.detail_perhitungan).map(([kode, detail]) => {
                                const nama = kode === 'K1' ? 'Publikasi' : kode === 'K2' ? 'SINTA' : kode === 'K3' ? 'Hibah' : 'Buku';
                                return `
                                    <div class="bg-yellow-50 p-3 rounded">
                                        <p class="text-sm font-medium text-gray-900">${kode} - ${nama}</p>
                                        <p class="text-sm text-gray-700 mt-1">
                                            ${detail.weight.toFixed(4)} × ${detail.normalized.toFixed(4)} = 
                                            <span class="font-semibold text-orange-600">${detail.weighted_score.toFixed(4)}</span>
                                        </p>
                                    </div>
                                `;
                            }).join('')}
                        </div>
                    </div>
                    
                    <!-- Langkah 5: Total Skor -->
                    <div class="border-2 border-blue-500 rounded-lg p-4 bg-blue-50">
                        <h5 class="font-semibold text-blue-900 mb-3">Langkah 5: Total Skor Akhir (Nilai V)</h5>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-700">Penjumlahan semua nilai terbobot:</p>
                                <p class="text-lg text-gray-800 mt-1 font-mono">
                                    ${Object.entries(item.detail_perhitungan).map(([k, d]) => d.weighted_score.toFixed(4)).join(' + ')}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">Skor Akhir</p>
                                <p class="text-4xl font-bold text-blue-600">${item.skor_akhir.toFixed(4)}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('detailContent').innerHTML = content;
            document.getElementById('detailModal').classList.remove('hidden');
        }
        
        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }
        
        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDetailModal();
            }
        });
    </script>
</x-app-layout>
