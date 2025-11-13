<x-app-layout>
    {{-- Modern Profile Page Design --}}
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50/30 to-gray-50">
        <div class="mx-auto max-w-5xl px-4 py-8">
            
            {{-- Breadcrumb Navigation --}}
            <nav class="mb-6 animate-fade">
                <ol class="flex items-center gap-2 text-sm text-gray-600">
                    <li><a href="{{ route('dosen.dashboard') }}" class="hover:text-blue-600 transition-colors">Dashboard</a></li>
                    <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                    <li class="font-semibold text-blue-600">Profile</li>
                </ol>
            </nav>

            {{-- Header --}}
            <div class="mb-8 animate-slide-up">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wider text-blue-600/70 font-semibold">Pengaturan Akun</p>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Profile Settings</h1>
                    </div>
                </div>
            </div>

            <div class="space-y-6 animate-fade">
                
                {{-- Section 1: Profile Information --}}
                <section class="rounded-2xl bg-white shadow-lg ring-1 ring-gray-200 overflow-hidden transition-all hover:shadow-xl">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-lg font-bold text-white">Informasi Profile</h2>
                                <p class="text-sm text-blue-100">Update nama dan email Anda</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                            @csrf
                        </form>

                        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('patch')

                            {{-- Name --}}
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Nama Lengkap <span class="text-red-600">*</span>
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       required 
                                       autofocus
                                       class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 hover:border-gray-400">
                                @if($errors->get('name'))
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                        </svg>
                                        {{ $errors->get('name')[0] }}
                                    </p>
                                @endif
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Email <span class="text-red-600">*</span>
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required
                                       class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 hover:border-gray-400">
                                @if($errors->get('email'))
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                        </svg>
                                        {{ $errors->get('email')[0] }}
                                    </p>
                                @endif

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <div class="mt-3 p-3 bg-amber-50 border-l-4 border-amber-500 rounded">
                                        <p class="text-sm text-amber-800">
                                            Email Anda belum diverifikasi.
                                            <button form="send-verification" class="underline text-amber-900 hover:text-amber-700 font-semibold">
                                                Klik di sini untuk mengirim ulang email verifikasi
                                            </button>
                                        </p>

                                        @if (session('status') === 'verification-link-sent')
                                            <p class="mt-2 text-sm font-semibold text-green-600 flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                                </svg>
                                                Link verifikasi baru telah dikirim ke email Anda
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            {{-- Submit Button --}}
                            <div class="flex items-center gap-4 pt-4">
                                <button type="submit" class="inline-flex items-center justify-center gap-1.5 px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-lg hover:shadow-xl transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Simpan Perubahan
                                </button>

                                @if (session('status') === 'profile-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 3000)"
                                        class="text-sm text-green-600 font-semibold flex items-center gap-1.5"
                                    >
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                        </svg>
                                        Tersimpan!
                                    </p>
                                @endif
                            </div>
                        </form>
                    </div>
                </section>

                {{-- Section 2: Update Password --}}
                <section class="rounded-2xl bg-white shadow-lg ring-1 ring-gray-200 overflow-hidden transition-all hover:shadow-xl">
                    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-lg font-bold text-white">Ubah Password</h2>
                                <p class="text-sm text-emerald-100">Gunakan password yang kuat untuk keamanan akun</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                            @csrf
                            @method('put')

                            {{-- Current Password --}}
                            <div>
                                <label for="update_password_current_password" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Password Saat Ini <span class="text-red-600">*</span>
                                </label>
                                <input type="password" 
                                       id="update_password_current_password" 
                                       name="current_password"
                                       autocomplete="current-password"
                                       class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 hover:border-gray-400">
                                @if($errors->updatePassword->get('current_password'))
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                        </svg>
                                        {{ $errors->updatePassword->get('current_password')[0] }}
                                    </p>
                                @endif
                            </div>

                            {{-- New Password --}}
                            <div>
                                <label for="update_password_password" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Password Baru <span class="text-red-600">*</span>
                                </label>
                                <input type="password" 
                                       id="update_password_password" 
                                       name="password"
                                       autocomplete="new-password"
                                       class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 hover:border-gray-400">
                                <p class="mt-2 text-xs text-gray-600">Minimal 8 karakter</p>
                                @if($errors->updatePassword->get('password'))
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                        </svg>
                                        {{ $errors->updatePassword->get('password')[0] }}
                                    </p>
                                @endif
                            </div>

                            {{-- Confirm Password --}}
                            <div>
                                <label for="update_password_password_confirmation" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Konfirmasi Password <span class="text-red-600">*</span>
                                </label>
                                <input type="password" 
                                       id="update_password_password_confirmation" 
                                       name="password_confirmation"
                                       autocomplete="new-password"
                                       class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 hover:border-gray-400">
                                @if($errors->updatePassword->get('password_confirmation'))
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                        </svg>
                                        {{ $errors->updatePassword->get('password_confirmation')[0] }}
                                    </p>
                                @endif
                            </div>

                            {{-- Submit Button --}}
                            <div class="flex items-center gap-4 pt-4">
                                <button type="submit" class="inline-flex items-center justify-center gap-1.5 px-5 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold shadow-lg hover:shadow-xl transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Update Password
                                </button>

                                @if (session('status') === 'password-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 3000)"
                                        class="text-sm text-green-600 font-semibold flex items-center gap-1.5"
                                    >
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                        </svg>
                                        Password berhasil diupdate!
                                    </p>
                                @endif
                            </div>
                        </form>
                    </div>
                </section>

                {{-- Section 3: Delete Account --}}
                <section class="rounded-2xl bg-white shadow-lg ring-1 ring-gray-200 overflow-hidden transition-all hover:shadow-xl">
                    <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-lg font-bold text-white">Hapus Akun</h2>
                                <p class="text-sm text-red-100">Tindakan ini tidak dapat dibatalkan</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded mb-6">
                            <p class="text-sm text-red-800">
                                <strong>Perhatian:</strong> Setelah akun dihapus, semua data dan resource akan dihapus secara permanen. Pastikan untuk mengunduh data penting sebelum menghapus akun.
                            </p>
                        </div>

                        <button
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                            class="inline-flex items-center justify-center gap-1.5 px-5 py-2.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold shadow-lg hover:shadow-xl transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus Akun
                        </button>
                    </div>
                </section>

            </div>

            {{-- Footer Spacing --}}
            <div class="h-8"></div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <div class="flex items-center gap-3 mb-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Konfirmasi Hapus Akun</h2>
                    <p class="text-sm text-gray-600">Apakah Anda yakin ingin menghapus akun?</p>
                </div>
            </div>

            <p class="text-sm text-gray-600 mb-6">
                Setelah akun dihapus, semua data dan resource akan dihapus secara permanen. Masukkan password Anda untuk konfirmasi penghapusan akun.
            </p>

            <div class="mb-6">
                <label for="password" class="block text-sm font-semibold text-gray-900 mb-2">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Masukkan password Anda"
                    class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-all focus:border-red-500 focus:ring-4 focus:ring-red-500/20 hover:border-gray-400"
                />

                @if($errors->userDeletion->get('password'))
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                        </svg>
                        {{ $errors->userDeletion->get('password')[0] }}
                    </p>
                @endif
            </div>

            <div class="flex justify-end gap-3">
                <button type="button"
                        x-on:click="$dispatch('close')"
                        class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg border-2 border-gray-300 bg-white text-gray-700 text-sm font-semibold shadow-sm hover:bg-gray-50 hover:border-gray-400 transition-all">
                    Batal
                </button>

                <button type="submit"
                        class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold shadow-lg hover:shadow-xl transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Ya, Hapus Akun Saya
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
