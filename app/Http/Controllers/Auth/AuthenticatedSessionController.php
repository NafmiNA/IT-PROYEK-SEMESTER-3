<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Clear any existing session data first
        $request->session()->flush();
        
        $request->authenticate();

        // Regenerate session to prevent session fixation
        $request->session()->regenerate();

        $user = $request->user();

        // Redirect based on user role - CHECK IN SPECIFIC ORDER
        if ($user?->role === 'admin') {
            // Admin goes to Filament admin panel
            return redirect('/admin');
        }

        if ($user?->role === 'mahasiswa') {
            // Mahasiswa goes to mahasiswa dashboard
            return redirect()->route('mahasiswa.dashboard');
        }

        if ($user?->role === 'dosen') {
            // Dosen goes to dosen dashboard
            return redirect()->route('dosen.dashboard');
        }

        // Fallback (should not happen with proper data)
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/login')->withErrors(['email' => 'Role tidak valid. Hubungi administrator.']);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Logout from all guards
        Auth::guard('web')->logout();
        
        // Invalidate and flush the session completely
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Return to login with no-cache headers
        return redirect('/login')
            ->withHeaders([
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
    }
}
