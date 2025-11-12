<x-app-layout>
    {{-- Ini akan mengisi bagian 'header' di layout Anda --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah User Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Menampilkan error validasi jika ada --}}
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <strong>Whoops!</strong> Ada masalah dengan input Anda.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form ini akan mengirim data ke UserController@store --}}
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf  {{-- Wajib untuk keamanan Laravel --}}

                        <div class="grid grid-cols-1 gap-6">
                            {{-- Field NAMA --}}
                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700">Nama</label>
                                <input type="text" name="name" id="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('name') }}" required>
                            </div>

                            {{-- Field EMAIL --}}
                            <div>
                                <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                                <input type="email" name="email" id="email" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('email') }}" required>
                            </div>

                            {{-- Field PASSWORD --}}
                            <div>
                                <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                                <input type="password" name="password" id="password" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                            </div>

                            {{-- Field KONFIRMASI PASSWORD --}}
                            <div>
                                <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                            </div>

                            {{-- Field ROLE --}}
                            <div>
                                <label for="role" class="block font-medium text-sm text-gray-700">Role</label>
                                <select name="role" id="role" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                    <option value="">Pilih Role</option>
                                    {{-- Sesuaikan role ini dengan diagram use case Anda --}}
                                    <option value="admin">Admin Koordinator P3M</option>
                                    <option value="dosen">Dosen</option>
                                    <option value="mahasiswa">Mahasiswa</option>
                                </select>
                            </div>

                            {{-- Tombol Submit --}}
                            <div class="flex justify-end mt-4">
                                <a href="{{ route('users.index') }}" class="btn btn-secondary mr-2">
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Simpan User
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>