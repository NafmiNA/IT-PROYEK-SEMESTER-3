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
            return redirect()->route('login');
        }

        if ($this->userMatchesRole($user, $role)) {
            return $next($request);
        }

        abort(403, 'AKSES DITOLAK. ANDA BUKAN ' . strtoupper($role) . '.');
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
