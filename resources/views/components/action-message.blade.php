@props(['on'])

<div x-data="{ shown: false, timeout: null }"
     x-init="@this.on('{{ $on }}', () => { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000); })"
     x-show="shown"
     x-transition:leave.opacity.duration.1000ms
     style="display: none;"
     {{ $attributes->merge(['class' => 'text-sm text-gray-600']) }}>
    {{-- Jika slot kosong, tampilkan 'Tersimpan.', jika tidak, tampilkan isi slot --}}
    {{ $slot->isEmpty() ? 'Tersimpan.' : $slot }}
</div>