@php
function getAhpLabel($val) {
    switch ($val) {
        case 9: return 'Mutlak Lebih Penting';
        case 7: return 'Jauh Lebih Penting';
        case 5: return 'Sangat Penting';
        case 3: return 'Cukup Penting';
        case 2: return 'Sedikit Lebih Penting';
        case 1: return 'Sama Penting';
        default: return '';
    }
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

        .ahp-btn {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .ahp-btn:hover {
            transform: translateY(-1px);
        }
        .ahp-btn:active {
            transform: translateY(0);
        }
    </style>

    <div class="min-h-screen bg-gray-50">
        
        {{-- Header --}}
        <header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    
                    {{-- Breadcrumb & Judul --}}
                    <div class="animate-fade">
                        <nav class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1 hover:text-blue-600 transition-colors">
                                Dashboard
                            </a>
                            <span class="text-gray-400">/</span>
                            <span class="font-medium text-gray-900">AHP</span>
                        </nav>
    
                        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                            <span class="bg-blue-100 text-blue-700 p-1.5 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </span>
                            Perbandingan Kriteria
                        </h1>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.prestasi.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-xl shadow-sm transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali
                        </a>

                        <form action="{{ route('admin.ahp.calculate') }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                Hitung Bobot
                            </button>
                        </form>
                        
                        @if($bobot->isNotEmpty())
                        <a href="{{ route('admin.ahp.results') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-xl shadow-sm transition-all">
                            Lihat Hasil
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            {{-- Alerts --}}
            @if(session('success'))
            <div class="mb-6 animate-fade">
                <div class="flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-xl shadow-sm text-green-800">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
            @endif

            {{-- Layout Container --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                {{-- Main Content: Comparison Form (Left/Top) --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Debug Info --}}
                    @if(count($pairs) > 0)
                        <div class="text-sm text-gray-500 px-1">
                            Memuat {{ count($pairs) }} perbandingan kriteria.
                        </div>
                    @endif

                    @forelse($pairs as $index => $pair)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200" id="card-{{ $index }}">
                        
                        {{-- Header Pair --}}
                        <div class="bg-gray-50 p-4 border-b border-gray-100">
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                {{-- Kiri --}}
                                <div class="w-full sm:flex-1 text-center cursor-pointer" onclick="selectSide({{ $index }}, 'left')">
                                    <div class="p-3 rounded-xl bg-blue-50 border border-blue-100 hover:border-blue-300 transition-colors">
                                        <h3 class="text-lg font-bold text-blue-900">{{ $pair['kriteria_a']->nama }}</h3>
                                        <p class="text-xs text-blue-600 mt-1">← Kiri (A)</p>
                                    </div>
                                </div>

                                {{-- VS --}}
                                <div class="flex-shrink-0">
                                    <span class="w-8 h-8 rounded-full bg-white border border-gray-300 flex items-center justify-center text-gray-500 font-bold text-xs">VS</span>
                                </div>

                                {{-- Kanan --}}
                                <div class="w-full sm:flex-1 text-center cursor-pointer" onclick="selectSide({{ $index }}, 'right')">
                                    <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-100 hover:border-emerald-300 transition-colors">
                                        <h3 class="text-lg font-bold text-emerald-900">{{ $pair['kriteria_b']->nama }}</h3>
                                        <p class="text-xs text-emerald-600 mt-1">Kanan (B) →</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Options Grid --}}
                        <div class="p-4">
                            <div class="grid grid-cols-2 gap-3">
                                {{-- Header Kolom --}}
                                <div class="text-xs font-bold text-blue-600 uppercase mb-2 pl-1">
                                    {{ $pair['kriteria_a']->nama }} Lebih Unggul
                                </div>
                                <div class="text-xs font-bold text-emerald-600 uppercase mb-2 text-right pr-1">
                                    {{ $pair['kriteria_b']->nama }} Lebih Unggul
                                </div>

                                @foreach([9, 7, 5, 3, 2] as $val)
                                    {{-- Kiri (A > B) --}}
                                    <button type="button" 
                                            onclick="setAhpValue({{ $index }}, {{ $val }})"
                                            id="btn-{{ $index }}-left-{{ $val }}"
                                            class="w-full flex items-center gap-2 px-3 py-2 rounded-lg border transition-all text-left
                                            {{ $pair['nilai'] == $val ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white border-gray-200 text-gray-700 hover:bg-blue-50' }}">
                                        <span class="text-lg font-bold w-6 text-center">{{ $val }}</span>
                                        <span class="text-xs leading-tight opacity-90">{{ getAhpLabel($val) }}</span>
                                    </button>

                                    {{-- Kanan (B > A) --}}
                                    @php $recip = 1/$val; @endphp
                                    <button type="button" 
                                            onclick="setAhpValue({{ $index }}, {{ number_format($recip, 5, '.', '') }})"
                                            id="btn-{{ $index }}-right-{{ $val }}"
                                            class="w-full flex flex-row-reverse items-center gap-2 px-3 py-2 rounded-lg border transition-all text-right
                                            {{ abs($pair['nilai'] - $recip) < 0.001 ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-white border-gray-200 text-gray-700 hover:bg-emerald-50' }}">
                                        <span class="text-lg font-bold w-6 text-center">{{ $val }}</span>
                                        <span class="text-xs leading-tight opacity-90">{{ getAhpLabel($val) }}</span>
                                    </button>
                                @endforeach
                            </div>

                            {{-- Sama Penting (Center) --}}
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <button type="button" 
                                        onclick="setAhpValue({{ $index }}, 1)"
                                        id="btn-{{ $index }}-center-1"
                                        class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg border transition-all
                                        {{ abs($pair['nilai'] - 1) < 0.001 ? 'bg-purple-600 border-purple-600 text-white' : 'bg-gray-50 border-gray-200 text-gray-700 hover:bg-gray-100' }}">
                                    <span class="text-lg font-bold">1</span>
                                    <span class="text-sm">Sama Penting</span>
                                </button>
                            </div>
                        </div>

                        {{-- Hidden Data --}}
                        <input type="hidden" id="pair-{{ $index }}-a" value="{{ $pair['kriteria_a']->id }}">
                        <input type="hidden" id="pair-{{ $index }}-b" value="{{ $pair['kriteria_b']->id }}">
                    </div>
                    @empty
                    <div class="text-center py-12 bg-white rounded-2xl border border-gray-200 border-dashed">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Belum ada Kriteria</h3>
                        <p class="text-gray-500 max-w-sm mx-auto mt-1">Silakan tambahkan minimal 2 kriteria pada menu Data Kriteria untuk memulai perbandingan.</p>
                    </div>
                    @endforelse

                </div>

                {{-- Sidebar Info (Right) --}}
                <div class="space-y-6">
                    
                    {{-- Status Panel --}}
                    @if($bobot->isNotEmpty())
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-bold text-gray-900">Hasil Perhitungan</h2>
                            @if($bobot->first()['is_consistent'])
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold uppercase rounded-full tracking-wide">Konsisten</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold uppercase rounded-full tracking-wide">Tidak Konsisten</span>
                            @endif
                        </div>

                        <div class="space-y-4">
                            @foreach($bobot as $b)
                            <div>
                                <div class="flex justify-between items-end mb-1">
                                    <span class="text-sm font-medium text-gray-600">{{ $b['nama'] }}</span>
                                    <span class="text-sm font-bold text-gray-900">{{ number_format($b['bobot_percent'], 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ $b['bobot_percent'] }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @if($bobot->first()['consistency_ratio'])
                        <div class="mt-6 pt-4 border-t border-gray-100">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-500">Consistency Ratio (CR)</span>
                                <span class="font-mono font-bold text-gray-700">{{ number_format($bobot->first()['consistency_ratio'], 4) }}</span>
                            </div>
                            @if(!$bobot->first()['is_consistent'])
                            <p class="mt-2 text-xs text-red-600 bg-red-50 p-2 rounded">
                                Nilai CR > 0.1. Mohon tinjau kembali perbandingan Anda agar lebih konsisten.
                            </p>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Guide --}}
                    <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100">
                        <h3 class="text-blue-900 font-bold mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Panduan Pengisian
                        </h3>
                        <p class="text-sm text-blue-700 leading-relaxed">
                            Pilih nilai yang merepresentasikan seberapa penting <strong>Kriteria A (Kiri)</strong> dibandingkan <strong>Kriteria B (Kanan)</strong>.
                            <br><br>
                            Contoh: Jika Anda memilih tombol <strong>"9 Mutlak"</strong> di sisi Kiri, artinya Kriteria A mutlak lebih penting daripada Kriteria B.
                        </p>
                    </div>

                </div>

            </div>
        </main>
    </div>

    <script>
        function setAhpValue(index, value) {
            // Update UI State
            updateActiveButton(index, value);

            // Get IDs
            const aId = document.getElementById(`pair-${index}-a`).value;
            const bId = document.getElementById(`pair-${index}-b`).value;

            // Save via AJAX
            saveComparison(aId, bId, value);
        }

        function selectSide(index, side) {
            // Helper for UX: if user clicks the header box, maybe highlight default "5" or just scroll to buttons
            // For now, let's just focus/animate slightly to indicate interactivity
        }

        function updateActiveButton(index, value) {
            // Reset all buttons in this card group
            const card = document.getElementById(`card-${index}`);
            const buttons = card.querySelectorAll('button');
            
            buttons.forEach(btn => {
                // Remove active classes
                btn.className = btn.className.replace(/bg-(blue|emerald|purple)-600/g, 'bg-white')
                                             .replace(/border-(blue|emerald|purple)-600/g, 'border-gray-100')
                                             .replace(/text-white/g, 'text-gray-600')
                                             .replace(/shadow-md/g, '')
                                             .replace(/ring-2/g, '')
                                             .replace(/bg-gray-50/g, 'bg-white'); // Fix reset
                
                // Add default hover classes back if missing (simplification)
                if (!btn.className.includes('hover:')) {
                    if (btn.id.includes('left')) btn.classList.add('hover:bg-blue-50', 'hover:border-blue-200');
                    if (btn.id.includes('right')) btn.classList.add('hover:bg-emerald-50', 'hover:border-emerald-200');
                    if (btn.id.includes('center')) btn.classList.add('hover:bg-gray-100', 'hover:border-gray-300');
                }
                
                // Reset text colors inside
                const spanLabel = btn.querySelector('span.text-sm');
                if(spanLabel) {
                     spanLabel.className = "text-sm font-medium text-gray-500";
                }
            });

            // Determine which button to activate
            let targetBtnId = '';
            let colorTheme = '';

            if (Math.abs(value - 1) < 0.001) {
                targetBtnId = `btn-${index}-center-1`;
                colorTheme = 'purple';
            } else if (value >= 1) {
                // Left side (A > B)
                targetBtnId = `btn-${index}-left-${value}`;
                colorTheme = 'blue';
            } else {
                // Right side (B > A), value is < 1, need to find the reciprocal integer
                const reciprocal = Math.round(1 / value);
                targetBtnId = `btn-${index}-right-${reciprocal}`;
                colorTheme = 'emerald';
            }

            const activeBtn = document.getElementById(targetBtnId);
            if (activeBtn) {
                // Apply active classes
                activeBtn.classList.remove('bg-white', 'text-gray-600', 'border-gray-100');
                
                // Remove hover classes to prevent conflict visual
                activeBtn.classList.remove('hover:bg-blue-50', 'hover:bg-emerald-50', 'hover:bg-gray-100');

                activeBtn.classList.add(`bg-${colorTheme}-600`, `border-${colorTheme}-600`, 'text-white', 'shadow-md', 'ring-2', `ring-${colorTheme}-200`, 'ring-offset-1');
                
                const spanLabel = activeBtn.querySelector('span.text-sm');
                if(spanLabel) {
                     spanLabel.classList.remove('text-gray-500');
                     spanLabel.classList.add(`text-${colorTheme}-50`);
                }
            }
        }

        function saveComparison(aId, bId, value) {
            fetch('{{ route("admin.ahp.saveComparison") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    kriteria_a_id: aId,
                    kriteria_b_id: bId,
                    nilai: value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Saved successfully');
                    // Optional: Show a small toast notification
                }
            })
            .catch(error => {
                console.error('Error saving:', error);
                alert('Gagal menyimpan perbandingan. Periksa koneksi internet Anda.');
            });
        }
    </script>
</x-app-layout>