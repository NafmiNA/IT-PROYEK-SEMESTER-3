<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                // Redirect based on user role
                if ($user->role === 'admin') {
                    return redirect('/admin');
                }
                
                if ($user->role === 'mahasiswa') {
                    return redirect()->route('mahasiswa.dashboard');
                }
                
                if ($user->role === 'dosen') {
                    return redirect()->route('dosen.dashboard');
                }
                
                // Default fallback
                return redirect('/dashboard');
            }
        }

        return $next($request);
    }
}
