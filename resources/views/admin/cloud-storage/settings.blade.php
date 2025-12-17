<x-app-layout>
    {{-- Header Section --}}
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-indigo-100 rounded-full">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                        {{ __('Pengaturan Cloud Storage') }}
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Kelola integrasi Google Drive dan struktur folder penyimpanan.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-md shadow-sm animate-fade-in-down">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-emerald-700 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-md shadow-sm animate-fade-in-down">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-rose-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-rose-700 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Left Column: Connection Status --}}
                <div class="md:col-span-1 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-300">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Status Koneksi</h3>
                            
                            <div class="flex flex-col items-center text-center space-y-4 py-4">
                                @if($isConnected)
                                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center animate-pulse">
                                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-lg font-bold text-green-700">Terhubung</p>
                                        <p class="text-sm text-gray-500 break-all">{{ $settings->email ?? 'Akun Terhubung' }}</p>
                                    </div>
                                    <form action="{{ route('admin.cloud-storage.disconnect') }}" method="POST" onsubmit="return confirm('Yakin ingin memutuskan koneksi?');" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-2 bg-white border border-rose-200 text-rose-600 rounded-lg hover:bg-rose-50 transition-colors text-sm font-medium flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Putuskan Koneksi
                                        </button>
                                    </form>
                                @else
                                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-lg font-bold text-gray-700">Belum Terhubung</p>
                                        <p class="text-sm text-gray-500">Hubungkan akun Google Drive</p>
                                    </div>
                                    <a href="{{ route('admin.cloud-storage.connect') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 focus:bg-blue-800 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                                        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"/>
                                        </svg>
                                        Hubungkan Sekarang
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Configuration --}}
                <div class="md:col-span-2 space-y-6">
                    @if($isConnected)
                        {{-- Auto Config Card --}}
                        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-300">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4 border-b pb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">Konfigurasi Folder Otomatis</h3>
                                </div>

                                <div class="flex items-start gap-4 mb-6">
                                    <div class="flex-shrink-0 p-3 bg-indigo-50 rounded-lg">
                                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-gray-600 text-sm leading-relaxed">
                                            Sistem akan menyiapkan struktur folder standar untuk aplikasi ini di Google Drive Anda.
                                            Jika folder utama <strong>SIDEPAN</strong> sudah ada, sistem akan membuat folder baru dengan nama unik (contoh: <strong>SIDEPAN 1</strong>).
                                        </p>
                                        <ul class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-2">
                                            <li class="flex items-center text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded border">
                                                <svg class="w-3 h-3 mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                                Dokumentasi
                                            </li>
                                            <li class="flex items-center text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded border">
                                                <svg class="w-3 h-3 mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                                Penelitian
                                            </li>
                                            <li class="flex items-center text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded border">
                                                <svg class="w-3 h-3 mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                                Pengabdian
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <form action="{{ route('admin.cloud-storage.save-folders') }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            style="background-color: #1d4ed8 !important; color: white !important;"
                                            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-lg font-semibold text-sm uppercase tracking-widest hover:opacity-90 focus:outline-none focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        Buat / Reset Folder Otomatis
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        {{-- Not Connected Placeholder --}}
                        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-300 h-full flex flex-col items-center justify-center p-8 text-center opacity-50">
                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <p class="text-gray-500 font-medium">Hubungkan Google Drive untuk mengakses pengaturan folder.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>