<x-app-layout>
    {{-- Ini akan mengisi bagian 'header' di layout Anda --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{-- Menampilkan nama user yang sedang diedit --}}
            {{ __('Edit User: ') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Menampilkan error validasi jika ada --}}
                    @if ($errors->any())
                        <div class="mb-4 p-3 rounded bg-red-50 text-red-700 border border-red-200">
                            <strong>Whoops!</strong> Ada masalah dengan input Anda.<br><br>
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- 
                      Form ini akan mengirim data ke UserController@update
                      Perhatikan:
                      1. Method-nya 'POST' tapi kita palsukan dengan @method('PUT')
                      2. action-nya mengarah ke route 'users.update' dengan ID user
                    --}}
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf      {{-- Wajib untuk keamanan --}}
                        @method('PUT') {{-- Wajib untuk method UPDATE --}}

                        <div class="grid grid-cols-1 gap-6">
                            {{-- Field NAMA --}}
                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700">Nama</label>
                                {{-- 
                                  Mengisi value dengan data lama:
                                  Gunakan old('name') DULU (jika ada error validasi), 
                                  jika tidak ada, BARU pakai data dari database ($user->name)
                                --}}
                                <input type="text" name="name" id="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('name', $user->name) }}" required>
                            </div>

                            {{-- Field EMAIL --}}
                            <div>
                                <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                                <input type="email" name="email" id="email" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('email', $user->email) }}" required>
                            </div>

                            {{-- Field PASSWORD --}}
                            <div>
                                <label for="password" class="block font-medium text-sm text-gray-700">Password Baru</label>
                                <input type="password" name="password" id="password" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">
                                <small class="text-gray-500">Kosongkan jika Anda tidak ingin mengubah password.</small>
                            </div>

                            {{-- Field KONFIRMASI PASSWORD --}}
                            <div>
                                <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">
                            </div>

                            {{-- Field ROLE --}}
                            <div>
                                <label for="role" class="block font-medium text-sm text-gray-700">Role</label>
                                <select name="role" id="role" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                    {{-- 
                                      Logika 'selected':
                                      Cek jika old('role') ada, jika tidak, gunakan $user->role.
                                      Lalu bandingkan dengan nilai <option>.
                                    --}}
                                    @php
                                        $selectedRole = old('role', $user->role);
                                    @endphp
                                    <option value="admin" @if($selectedRole == 'admin') selected @endif>Admin Koordinator P3M</option>
                                    <option value="dosen" @if($selectedRole == 'dosen') selected @endif>Dosen</option>
                                    <option value="mahasiswa" @if($selectedRole == 'mahasiswa') selected @endif>Mahasiswa</option>
                                </select>
                            </div>

                            {{-- Tombol Submit --}}
                            <div class="flex justify-end mt-4">
                                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 transition hover:bg-gray-100 mr-2">
                                    Batal
                                </a>
                                <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-[#2050A0] px-5 py-2 text-sm font-semibold text-white shadow-md transition hover:bg-[#163B78]">
                                    Update User
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>