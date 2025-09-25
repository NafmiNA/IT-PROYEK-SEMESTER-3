{{-- resources/views/dosen/penelitian/create.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <h2>Tambah Pengabdian</h2>
  </x-slot>

  <div class="p-6 max-w-3xl mx-auto">
    <div class="bg-white shadow rounded-lg p-4 border">
      <p class="text-gray-500">Form tambah (placeholder).</p>
      <a href="{{ route('dosen.pengabdian.index') }}" class="text-blue-600">← Kembali</a>
    </div>
  </div>
</x-app-layout>
-y-1">
                <!-- Profile -->
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>      