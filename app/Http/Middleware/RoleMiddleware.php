<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();

        if (!$user) {
            $request->session()->flush();
            return redirect()->route('login');
        }

        if ($this->userMatchesRole($user, $role)) {
            return $next($request);
        }

        // User is authenticated but accessing wrong role area
        // Redirect to their correct dashboard instead of 403
        if ($user->role === 'admin') {
            return redirect('/admin');
        }
        
        if ($user->role === 'mahasiswa') {
            return redirect()->route('mahasiswa.dashboard');
        }
        
        if ($user->role === 'dosen') {
            return redirect()->route('dosen.dashboard');
        }

        // If role is invalid, logout and redirect
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->withErrors(['error' => 'Akses ditolak. Role tidak sesuai.']);
    }

    protected function userMatchesRole($user, string $role): bool
    {
        if ($user->role === $role) {
            return true;
        }

        if ($role === 'dosen') {
            if (!$user->relationLoaded('dosen')) {
                $user->load('dosen');
            }

            return (bool) $user->dosen;
        }

        return false;
    }
}
