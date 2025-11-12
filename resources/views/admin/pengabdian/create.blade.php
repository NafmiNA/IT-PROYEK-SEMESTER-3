<x-app-layout>
    {{-- Modern UX-focused Layout matching Penelitian form --}}
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50/30 to-gray-50">
        <div class="mx-auto max-w-5xl px-4 py-8">
            
            {{-- Breadcrumb Navigation --}}
            <nav class="mb-6 animate-fade">
                <ol class="flex items-center gap-2 text-sm text-gray-600">
                    {{-- MODIFIKASI: Rute diubah ke admin.dashboard --}}
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition-colors">Dashboard</a></li>
                    <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                    {{-- MODIFIKASI: Rute diubah ke admin.pengabdian.index --}}
                    <li><a href="{{ route('admin.pengabdian.index') }}" class="hover:text-blue-600 transition-colors">Pengabdian</a></li>
                    <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                    <li class="font-semibold text-blue-600">Tambah Baru</li>
                </ol>
            </nav>

            {{-- Header with Back Button --}}
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-8 animate-slide-up">
                <div class="flex items-center gap-3">
                    {{-- MODIFIKASI: Rute diubah ke admin.pengabdian.index --}}