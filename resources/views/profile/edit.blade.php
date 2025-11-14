<x-app-layout>
    {{-- REFAKTOR: Menambahkan style konsisten dari halaman lain --}}
    <style>
        @keyframes slideUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        
        .animate-slide-up { animation: slideUp 0.3s ease-out both; }
        .animate-fade { animation: fadeIn 0.4s ease-out both; }

        .focus-visible:focus-visible { 
            outline: 3px solid #3b82f6; 
            outline-offset: 2px; 
        }
        
        .bg-subtle {
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(59, 130, 246, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 90% 80%, rgba(147, 51, 234, 0.04) 0%, transparent 50%);
        }
    </style>

    {{-- REFAKTOR: Mengganti div utama agar konsisten --}}
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 bg-subtle">

        {{-- REFAKTOR: Menghapus <x-slot name="header"> dan mengganti dengan header admin --}}
        <header class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    
                    {{-- Kiri: Breadcrumb & Judul --}}
                    <div class="animate-fade">
                        {{-- Breadcrumb --}}
                        <nav class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                            {{-- Asumsi user adalah admin, sesuaikan jika perlu (cth: dosen.dashboard) --}}
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
                            {{-- PERUBAHAN: Diubah ke 'Profil' --}}
                            <span class="font-medium text-gray-900">Profil</span>
                        </nav>
    
                        {{-- Judul & Tombol Kembali --}}
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="group flex-shrink-0 inline-flex items-center justify-center w-11 h-11 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-md hover:shadow-lg transition-all duration-200 focus-visible"
                               aria-label="Kembali ke Dashboard">
                                <svg class="w-5 h-5 transform group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                            </a>
                            <div>
                                {{-- PERUBAHAN: Diubah ke 'Profil' --}}
                                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                                    Profil
                                </h1>
                                <p class="text-sm text-gray-600 mt-1">
                                    Perbarui informasi profil dan kata sandi Anda.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Bagian kanan header sengaja dikosongkan (tidak perlu search/add) --}}

                </div>
            </div>
        </header>

        {{-- REFAKTOR: Mengganti wrapper konten utama agar konsisten --}}
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

            {{-- REFAKTOR: Tombol kembali manual DIHAPUS karena sudah ada di header --}}
            
            <div class="space-y-6">
                {{-- REFAKTOR: Card style disesuaikan --}}
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <div class="p-5 sm:p-6">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                {{-- REFAKTOR: Card style disesuaikan --}}
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <div class="p-5 sm:p-6">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                {{-- REFAKTOR: Card style disesuaikan --}}
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <div class="p-5 sm:p-6">
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>