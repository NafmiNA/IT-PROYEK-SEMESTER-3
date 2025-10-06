@props([
    'header' => null,
    'backUrl' => null,
    'backLabel' => 'Kembali',
])

@php
    $resolvedBackUrl = $backUrl ?? null;
@endphp

<div class="min-h-screen bg-gray-100">
    @include('layouts.navigation')

    @if ($header)
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="flex-1 w-full">
                    {{ $header }}
                </div>
                <div class="flex justify-end w-full md:w-auto">
                    @if ($resolvedBackUrl)
                    <a href="{{ $resolvedBackUrl }}"
                       class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-600 shadow-sm transition hover:bg-gray-100"
                       aria-label="{{ $backLabel }}">
                        <span class="text-lg">←</span>
                        <span class="hidden sm:inline">{{ $backLabel }}</span>
                    </a>
                    @endif
                </div>
            </div>
        </header>
    @endif

    <main>
        {{ $slot }}
    </main>
</div>
