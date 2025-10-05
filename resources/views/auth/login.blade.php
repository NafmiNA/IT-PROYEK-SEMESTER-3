<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Masuk & Verifikasi</title>
  <!-- Tailwind -->
  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
  <style>
    html, body { height: 100%; overflow: hidden; }
    .login-card { border: 3px solid #2050A0; }
  </style>
</head>
<body class="bg-gray-100 text-gray-800">

  <!-- Grid dua kolom: form kiri, gambar kanan -->
  <div class="min-h-screen h-screen grid grid-cols-1 lg:grid-cols-12 gap-x-8 px-4 lg:px-8">

    <!-- 🔹 KIRI: Form login -->
    <main class="lg:col-span-6 flex items-center justify-center py-4">
      <div class="w-full max-w-sm">
        <div class="login-card bg-white rounded-2xl shadow-lg p-6">
          
          <!-- Logo & Judul -->
          <div class="flex flex-col items-center mb-5">
            <img src="{{ asset('images/logo-full.png') }}" alt="Logo" class="h-14 mb-2">
            <h1 class="text-xl font-bold text-[#2050A0] text-center">Masuk dan Verifikasi</h1>
            <p class="text-xs text-gray-600 text-center mt-1 leading-snug">
              Nikmati kemudahan sistem autentikasi tunggal untuk mengakses semua layanan dengan satu akun.
            </p>
          </div>

          <!-- Tombol Google -->
          <a href="{{ route('auth.google.redirect', []) ?? '#' }}"
             class="w-full border border-gray-300 rounded-lg py-2.5 px-4 flex items-center justify-center gap-3 hover:bg-gray-50 transition mb-4">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="h-4 w-4" alt="">
            <span class="font-semibold text-gray-700 text-sm">Masuk dengan Google</span>
          </a>

          <!-- Garis Pembatas -->
          <div class="flex items-center gap-2 mb-4">
            <div class="h-px bg-gray-200 w-full"></div>
            <span class="text-gray-500 text-xs">atau</span>
            <div class="h-px bg-gray-200 w-full"></div>
          </div>

          <!-- Form -->
          <form method="POST" action="{{ route('login') }}" class="space-y-4 text-sm">
            @csrf

            <div>
              <label class="block font-semibold mb-1">Email/akun pengguna <span class="text-red-500">*</span></label>
              <input type="text" name="email"
                     class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
                            focus:outline-none focus:ring-2 focus:ring-[#2050A0]/20 focus:border-[#2050A0]
                            placeholder:text-gray-400"
                     placeholder="Masukkan email/username" required value="{{ old('email') }}">
            </div>

            <div>
              <label class="block font-semibold mb-1">Password <span class="text-red-500">*</span></label>
              <div class="relative">
                <input id="password" type="password" name="password"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 pr-10 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#2050A0]/20 focus:border-[#2050A0]
                              placeholder:text-gray-400"
                       placeholder="Masukkan password" required>
                <button type="button"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#2050A0]"
                        onclick="const p=document.getElementById('password');p.type=p.type==='password'?'text':'password'">
                  👁️
                </button>
              </div>
            </div>

            @if ($errors->any())
              <div class="text-red-600 text-xs">{{ $errors->first() }}</div>
            @endif

            <div class="flex justify-end -mt-1">
              <a href="{{ route('password.request') }}" class="text-xs text-[#2050A0] hover:underline font-medium">
                Lupa kata sandi?
              </a>
            </div>

            <button type="submit"
                    class="w-full bg-[#2050A0] hover:bg-[#163B78] text-white font-semibold rounded-lg py-2.5 shadow-md hover:shadow-lg transition">
              Masuk
            </button>
          </form>

          <p class="text-center text-[10px] text-gray-500 mt-4">
            Dikembangkan oleh <span class="font-semibold">KELOMPOK 2</span> (Tugas Projek PBL)
          </p>
        </div>
      </div>
    </main>

    <!-- 🔹 KANAN: Gambar gedung -->
    <aside class="hidden lg:block lg:col-span-6 py-4">
      <div class="h-full w-full relative">
        <img src="{{ asset('images/gedung-ti.jpeg') }}"
             alt="Gedung Teknik Informatika"
             class="h-full w-full object-cover rounded-xl">
        <div class="absolute bottom-6 left-6 bg-black/60 text-white p-4 rounded-lg backdrop-blur-sm max-w-[520px]">
          <p class="text-xs tracking-wider uppercase text-lime-300">Selamat Datang</p>
          <h2 class="text-xl font-semibold leading-tight">
            Sistem Informasi Pengelolaan Data Penelitian dan Pengabdian Dosen<br>
            <span class="font-extrabold">POLITEKNIK NEGERI TANAH LAUT</span>
          </h2>
        </div>
      </div>
    </aside>

  </div>
</body>
</html>
