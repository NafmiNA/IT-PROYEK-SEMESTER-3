<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.prestasi.index') }}" 
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
            <a href="{{ route('admin.prestasi.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Prestasi
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
                    
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="{{ route('admin.saw.ranking', ['tahun' => $tahun ?? now()->year]) }}" 
                           class="inline-flex items-center gap-3 px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xl rounded-lg shadow-xl transition transform hover:scale-105">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            LIHAT RANKING
                        </a>

                        <a href="{{ route('admin.saw.export', ['tahun' => $tahun ?? now()->year]) }}" 
                           class="inline-flex items-center gap-3 px-8 py-4 bg-green-700 hover:bg-green-800 text-white font-bold text-xl rounded-lg shadow-xl transition transform hover:scale-105">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            EXPORT EXCEL
                        </a>
                    </div>

                    <p class="text-gray-600 text-sm mt-2">Data dari {{ $prestasi->count() }} dosen siap dihitung</p>
                </div>
            </div>

            <!-- Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-start gap-4">
                    <svg class="w-8 h-8 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-blue-900 mb-2 text-lg">Informasi Pengelolaan Data</h3>
                        <p class="text-sm text-blue-800 mb-2">
                            <strong>Publikasi & Hibah:</strong> Dihitung secara <strong>OTOMATIS</strong> oleh sistem berdasarkan data Penelitian dan Pengabdian yang berstatus 'Disetujui' pada tahun terkait.
                        </p>
                        <p class="text-sm text-blue-800">
                            <strong>Skor Sinta & Buku:</strong> Diinput manual oleh <strong>ADMIN</strong>. Klik tombol "Edit" pada tabel di bawah untuk memperbarui data ini.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Data Rekap -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Rekap Data Prestasi Dosen</h3>
                        <button onclick="openCreateModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                            + Tambah Data Manual
                        </button>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Data Prestasi Tahun {{ $tahun }}
                    </h3>
                    
                    @if($prestasi->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-lg font-medium">Belum ada data prestasi untuk tahun {{ $tahun }}</p>
                            <p class="text-sm">Silakan tambahkan data manual jika diperlukan</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Publikasi (Auto)</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">SINTA (Input)</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hibah (Auto)</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Buku (Input)</th>
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
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 bg-gray-50 font-mono">{{ $p->publikasi }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 font-bold">{{ $p->skor_sinta }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 bg-gray-50 font-mono">Rp {{ number_format($p->hibah, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 font-bold">{{ $p->buku }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <button onclick="openEditModal({{ $p->id }}, '{{ $p->dosen->nama }}', {{ $p->skor_sinta }}, {{ $p->buku }})" 
                                                        class="text-blue-600 hover:text-blue-900 font-semibold hover:underline">
                                                    Edit
                                                </button>
                                                <form action="{{ route('admin.saw.destroy', $p->id) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('Hapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 hover:underline">Hapus</button>
                                                </form>
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

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="relative bg-white rounded-lg shadow-xl p-8 w-full max-w-md">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Edit Data Prestasi</h3>
            <p class="text-sm text-gray-600 mb-6">Dosen: <span id="modalDosenName" class="font-semibold"></span></p>
            
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Skor SINTA</label>
                    <input type="number" name="skor_sinta" id="modalSinta" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required min="0">
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Buku</label>
                    <input type="number" name="buku" id="modalBuku" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required min="0">
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Modal (Untuk inisialisasi data dosen yang belum ada di list) -->
    <div id="createModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="relative bg-white rounded-lg shadow-xl p-8 w-full max-w-md">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Tambah Data Prestasi</h3>
            
            <form method="POST" action="{{ route('admin.saw.store') }}">
                @csrf
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Dosen</label>
                    <select name="dosen_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" required>
                        <option value="">-- Pilih Dosen --</option>
                        @foreach($dosens as $d)
                            <option value="{{ $d->id }}">{{ $d->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Skor SINTA</label>
                    <input type="number" name="skor_sinta" value="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required min="0">
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Buku</label>
                    <input type="number" name="buku" value="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required min="0">
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, nama, sinta, buku) {
            document.getElementById('modalDosenName').innerText = nama;
            document.getElementById('modalSinta').value = sinta;
            document.getElementById('modalBuku').value = buku;
            
            // Set action URL dynamically
            const form = document.getElementById('editForm');
            form.action = "{{ route('admin.saw.update', ':id') }}".replace(':id', id);
            
            document.getElementById('editModal').classList.remove('hidden');
        }

        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }
        
        // Close modals on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('editModal').classList.add('hidden');
                document.getElementById('createModal').classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
