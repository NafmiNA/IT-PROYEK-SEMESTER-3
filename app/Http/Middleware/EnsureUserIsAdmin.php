<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and has admin role
        if (!$request->user() || $request->user()->role !== 'admin') {
            // If not admin, redirect to appropriate dashboard or login
            if ($request->user()) {
                // User is logged in but not admin
                if ($request->user()->role === 'dosen') {
                    return redirect()->route('dosen.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
                } elseif ($request->user()->role === 'mahasiswa') {
                    return redirect()->route('mahasiswa.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
                }
                
                // Default redirect
                return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
            }
            
            // Not logged in, redirect to login
            return redirect('/login')->with('error', 'Silakan login sebagai admin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
