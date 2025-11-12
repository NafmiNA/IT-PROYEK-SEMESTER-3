<?php

namespace App\Http\Controllers;

use App\Models\User; // <-- MODIFIKASI: Menggunakan Model User (bukan Dosen)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // <-- TAMBAHAN: Ini penting untuk 'password'

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua user.
     */
    public function index()
    {
        // MODIFIKASI: Mengambil data User, bukan Dosen
        $users = User::all(); 
        
        // MODIFIKASI: Mengarah ke view 'users.index' (yang akan Anda buat)
        return view('users.index', compact('users')); 
    }

    /**
     * Menampilkan form untuk membuat user baru.
     */
    public function create()
    {
        // MODIFIKASI: Mengarah ke view 'users.create'
        return view('users.create');
    }

    /**
     * Menyimpan user baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi data (sesuaikan dengan form Anda)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string', // Pastikan 'role' ada di $fillable Model User
        ]);

        // MODIFIKASI: Membuat User baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // <-- PENTING: Password harus di-hash
            'role' => $request->role,
        ]);

        // MODIFIKASI: Redirect ke 'users.index'
        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit user.
     */
    public function edit(string $id)
    {
        // MODIFIKASI: Mengambil data User
        $user = User::findOrFail($id); 
        
        // MODIFIKASI: Mengarah ke view 'users.edit'
        return view('users.edit', compact('user'));
    }

    /**
     * Update data user di database.
     */
    public function update(Request $request, string $id)
    {
        // MODIFIKASI: Mengambil data User
        $user = User::findOrFail($id);

        // Validasi data (email harus unik, tapi abaikan user saat ini)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed', // Password boleh kosong (tidak diubah)
            'role' => 'required|string',
        ]);

        // Siapkan data untuk di-update
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // TAMBAHAN: Hanya update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // MODIFIKASI: Redirect ke 'users.index'
        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Menghapus user dari database.
     */
    public function destroy(string $id)
    {
        // MODIFIKASI: Mengambil data User
        $user = User::findOrFail($id);
        $user->delete();

        // MODIFIKASI: Redirect ke 'users.index'
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}