@php
// Helper function to convert AHP value to slider position (1-17)
function getSliderPosition($value) {
    $mapping = [
        9 => 1, 8 => 2, 7 => 3, 6 => 4, 5 => 5, 4 => 6, 3 => 7, 2 => 8, 1 => 9,
        0.5 => 10, 0.333 => 11, 0.25 => 12, 0.2 => 13, 0.167 => 14, 0.143 => 15, 0.125 => 16, 0.111 => 17
    ];
    
    // Find closest match
    $closestDiff = PHP_FLOAT_MAX;
    $closestPos = 9;
    foreach ($mapping as $val => $pos) {
        $diff = abs($value - $val);
        if ($diff < $closestDiff) {
            $closestDiff = $diff;
            $closestPos = $pos;
        }
    }
    return $closestPos;
}
@endphp

<x-app-layout>
    <style>
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .animate-fade { animation: fadeIn 0.4s ease-out both; }
        
        .focus-visible:focus-visible { 
            outline: 2px solid #3b82f6; 
            outline-offset: 2px; 
        }

        /* Slider styling */
        input[type="range"] {
            -webkit-appearance: none;
            appearance: none;
            width: 100% !important;
            height: 8px !important;
            border-radius: 5px;  
            background: linear-gradient(to right, #ef4444 0%, #f59e0b 25%, #eab308 50%, #10b981 75%, #3b82f6 100%) !important;
            outline: none;
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 24px;
            height: 24px;
            border-radius: 50%; 
            background: #3b82f6;
            cursor: pointer;
            border: 3px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        input[type="range"]::-moz-range-thumb {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #3b82f6;
            cursor: pointer;
            border: 3px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
    </style>

    <div class="min-h-screen bg-gray-50">
        
        {{-- Header --}}
        <header class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    
                    {{-- Kiri: Breadcrumb & Judul --}}
                    <div class="animate-fade">
                        {{-- Breadcrumb --}}
                        <nav class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="flex items-center gap-1 hover:text-blue-600 transition-colors focus-visible">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Dashboard
                            </a>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <a href="{{ route('admin.prestasi.index') }}" 
                               class="hover:text-blue-600 transition-colors">
                                Prestasi
                            </a>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span class="font-medium text-gray-900">Kelola Bobot Kriteria</span>
                        </nav>
    
                        {{-- Judul & Tombol Kembali --}}
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.prestasi.index') }}" 
                               class="group flex-shrink-0 inline-flex items-center justify-center w-11 h-11 rounded-lg bg-purple-600 hover:bg-purple-700 text-white shadow-md hover:shadow-lg transition-all duration-200 focus-visible">
                                <svg class="w-5 h-5 transform group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                            </a>
                            <div>
                                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                                    Kelola Bobot Kriteria (AHP)
                                </h1>
                                <p class="text-sm text-gray-600 mt-1">
                                    Tentukan tingkat kepentingan antar kriteria
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

            {{-- Alert Success --}}
            @if(session('success'))
            <div class="mb-6">
                <div class="flex items-start gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-lg shadow-sm">
                    <div class="flex-shrink-0 w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-emerald-900">Berhasil!</p>
                        <p class="text-sm text-emerald-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Alert Error --}}
            @if(session('error'))
            <div class="mb-6">
                <div class="flex items-start gap-3 px-4 py-3 bg-red-50 border border-red-200 rounded-lg shadow-sm">
                    <div class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-red-900">Error!</p>
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Kolom Kiri: Penjelasan & Hasil Bobot --}}
                <div class="lg:col-span-1 space-y-6">
                    
                    {{-- Info Panel --}}
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                        <h2 class="text-lg font-semibold text-gray-900 mb-3">Skala Saaty</h2>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-xs">Nilai</span>
                                <span class="text-gray-600 text-xs">Keterangan</span>
                            </div>
                            <div class="h-px bg-gray-200 my-2"></div>
                            <div class="flex justify-between">
                                <span class="font-medium">1</span>
                                <span class="text-gray-600">Sama penting</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">2</span>
                                <span class="text-gray-600 text-xs">Antara sama & sedikit</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">3</span>
                                <span class="text-gray-600">Sedikit lebih penting</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">4</span>
                                <span class="text-gray-600 text-xs">Antara sedikit & lebih</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">5</span>
                                <span class="text-gray-600">Lebih penting</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">6</span>
                                <span class="text-gray-600 text-xs">Antara lebih & sangat</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">7</span>
                                <span class="text-gray-600">Sangat lebih penting</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">8</span>
                                <span class="text-gray-600 text-xs">Antara sangat & mutlak</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">9</span>
                                <span class="text-gray-600">Mutlak lebih penting</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-3 pt-3 border-t">
                                Geser ke kiri untuk kriteria pertama lebih penting (9:1 - 1:1), ke kanan untuk kriteria kedua lebih penting (1:1 - 1:9).
                            </p>
                        </div>
                    </div>

                    {{-- Hasil Bobot --}}
                    @if($bobot->isNotEmpty())
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Bobot Kriteria</h2>
                            @if($bobot->first()['is_consistent'])
                                <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full">Konsisten</span>
                            @else
                                <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full">Perlu Revisi</span>
                            @endif
                        </div>
                        
                        <div class="space-y-3">
                            @foreach($bobot as $b)
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ $b['nama'] }}</span>
                                    <span class="text-sm font-bold text-gray-900">{{ number_format($b['bobot_percent'], 2) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $b['bobot_percent'] }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @if($bobot->first()['consistency_ratio'])
                        <div class="mt-4 pt-4 border-t text-xs text-gray-500">
                            <div class="flex justify-between">
                                <span>Consistency Ratio:</span>
                                <span class="font-medium">{{ number_format($bobot->first()['consistency_ratio'], 4) }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Kriteria List --}}
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                        <h2 class="text-lg font-semibold text-gray-900 mb-3">Daftar Kriteria</h2>
                        <div class="space-y-2">
                            @foreach($kriteria as $k)
                            <div class="flex items-start gap-2">
                                <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-bold">
                                    {{ $k->kode }}
                                </span>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $k->nama }}</p>
                                    <p class="text-xs text-gray-500">{{ $k->deskripsi }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- Kolom Kanan: Form Perbandingan --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                        
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-2">Perbandingan Kriteria</h2>
                            <p class="text-sm text-gray-600">
                                Geser slider untuk menentukan tingkat kepentingan kriteria satu terhadap yang lain. 
                                Jika ubah salah satu perbandingan, sistem akan otomatis recalculate semua bobot.
                            </p>
                        </div>

                        <form id="ahpForm" class="space-y-6">
                            @csrf
                            
                            @forelse($pairs as $index => $pair)
                            <div class="border-b border-gray-200 pb-6">
                                <div class="mb-3">
                                    <p class="text-sm font-medium text-gray-700 mb-1">
                                        Seberapa penting <span class="text-blue-600 font-bold">{{ $pair['kriteria_a']->nama }}</span> 
                                        dibanding <span class="text-purple-600 font-bold">{{ $pair['kriteria_b']->nama }}</span>?
                                    </p>
                                </div>

                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0 w-32 text-right">
                                        <span class="text-sm font-medium text-blue-600">{{ $pair['kriteria_a']->nama }}</span>
                                    </div>
                                    
                                    <div class="flex-1">
                                        <input type="range" 
                                               min="1" 
                                               max="17" 
                                               step="1"
                                               value="{{ getSliderPosition($pair['nilai']) }}"
                                               data-a-id="{{ $pair['kriteria_a']->id }}"
                                               data-b-id="{{ $pair['kriteria_b']->id }}"
                                               data-real-value="{{ $pair['nilai'] }}"
                                               class="comparison-slider w-full"
                                               onchange="updateComparison(this)">
                                        
                                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                                            <span>9:1</span>
                                            <span>8:1</span>
                                            <span>7:1</span>
                                            <span>6:1</span>
                                            <span>5:1</span>
                                            <span>4:1</span>
                                            <span>3:1</span>
                                            <span>2:1</span>
                                            <span>1:1</span>
                                            <span>1:2</span>
                                            <span>1:3</span>
                                            <span>1:4</span>
                                            <span>1:5</span>
                                            <span>1:6</span>
                                            <span>1:7</span>
                                            <span>1:8</span>
                                            <span>1:9</span>
                                        </div>
                                    </div>

                                    <div class="flex-shrink-0 w-32">
                                        <span class="text-sm font-medium text-purple-600">{{ $pair['kriteria_b']->nama }}</span>
                                    </div>
                                </div>

                                <div class="mt-2 text-center">
                                    <span class="text-sm font-bold text-gray-700" id="label-{{ $index }}">
                                        {{ $pair['nilai'] == 1 ? 'Sama penting' : ($pair['nilai'] > 1 ? $pair['kriteria_a']->nama . ' lebih penting' : $pair['kriteria_b']->nama . ' lebih penting') }}
                                    </span>
                                    <span class="text-sm text-gray-500 ml-2" id="value-{{ $index }}">(Nilai: {{ number_format($pair['nilai'], 3) }})</span>
                                </div>
                            </div>
                            @empty
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                                <svg class="w-12 h-12 text-yellow-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <p class="font-semibold text-yellow-900 mb-2">Tidak Ada Data Kriteria</p>
                                <p class="text-sm text-yellow-700">Silakan tambahkan minimal 2 kriteria untuk menggunakan fitur AHP.</p>
                            </div>
                            @endforelse

                        </form>

                        <div class="mt-6 flex gap-3">
                            <form action="{{ route('admin.ahp.calculate') }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    Hitung Bobot Kriteria
                                </button>
                            </form>

                            @if($bobot->isNotEmpty())
                            <a href="{{ route('admin.ahp.results') }}" 
                               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Lihat Detail
                            </a>
                            @endif
                        </div>

                    </div>
                </div>

            </div>

        </main>
    </div>

    <script>
        // Mapping slider position (1-17) to AHP values
        const sliderToValue = {
            1: 9,      // 9:1
            2: 8,      // 8:1
            3: 7,      // 7:1
            4: 6,      // 6:1
            5: 5,      // 5:1
            6: 4,      // 4:1
            7: 3,      // 3:1
            8: 2,      // 2:1
            9: 1,      // 1:1
            10: 0.5,   // 1:2
            11: 0.333, // 1:3
            12: 0.25,  // 1:4
            13: 0.2,   // 1:5
            14: 0.167, // 1:6
            15: 0.143, // 1:7
            16: 0.125, // 1:8
            17: 0.111  // 1:9
        };

        // Update comparison via AJAX when slider changes
        function updateComparison(slider) {
            const aId = slider.dataset.aId;
            const bId = slider.dataset.bId;
            const sliderPos = parseInt(slider.value);
            const nilai = sliderToValue[sliderPos];
            
            // Update label
            const index = Array.from(document.querySelectorAll('.comparison-slider')).indexOf(slider);
            const labelEl = document.getElementById(`label-${index}`);
            const valueEl = document.getElementById(`value-${index}`);
            
            const sliders = document.querySelectorAll('.comparison-slider');
            const currentSlider = sliders[index];
            const aName = currentSlider.parentElement.parentElement.querySelector('.text-blue-600').textContent.trim();
            const bName = currentSlider.parentElement.parentElement.querySelector('.text-purple-600').textContent.trim();
            
            // Determine label based on position
            if (sliderPos === 9) {
                labelEl.textContent = 'Sama penting';
                valueEl.textContent = '(1:1)';
            } else if (sliderPos < 9) {
                // A lebih penting dari B
                const labels = {
                    1: 'mutlak lebih penting',
                    2: 'sangat-mutlak lebih penting',
                    3: 'sangat lebih penting',
                    4: 'lebih-sangat penting',
                    5: 'lebih penting',
                    6: 'sedikit-lebih penting',
                    7: 'sedikit lebih penting',
                    8: 'sedikit penting'
                };
                labelEl.textContent = aName + ' ' + labels[sliderPos];
                valueEl.textContent = `(${Math.round(nilai)}:1)`;
            } else {
                // B lebih penting dari A (sliderPos > 9)
                const reciprocal = 1 / nilai;
                const labels = {
                    10: 'sedikit penting',
                    11: 'sedikit lebih penting',
                    12: 'sedikit-lebih penting',
                    13: 'lebih penting',
                    14: 'lebih-sangat penting',
                    15: 'sangat lebih penting',
                    16: 'sangat-mutlak lebih penting',
                    17: 'mutlak lebih penting'
                };
                labelEl.textContent = bName + ' ' + labels[sliderPos];
                valueEl.textContent = `(1:${Math.round(reciprocal)})`;
            }

            // Save via AJAX
            fetch('{{ route("admin.ahp.saveComparison") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    kriteria_a_id: aId,
                    kriteria_b_id: bId,
                    nilai: nilai
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Perbandingan tersimpan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>

</x-app-layout>
