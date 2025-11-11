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
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        // Redirect based on user role
        if ($user?->role === 'admin') {
            // Admin goes to Filament admin panel
            return redirect()->intended('/admin');
        }

        if ($user?->role === 'mahasiswa' && Route::has('mahasiswa.dashboard')) {
            return redirect()->intended(route('mahasiswa.dashboard', absolute: false));
        }

        if ($user && ($user->role === 'dosen' || $user->dosen()->exists())) {
            return redirect()->intended(route('dosen.dashboard', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Clear all auth guards
        Auth::guard('web')->logout();
        
        // Invalidate the session
        $request->session()->invalidate();
        
        // Regenerate CSRF token
        $request->session()->regenerateToken();
        
        // Flush all session data
        $request->session()->flush();
        
        // Clear authentication data
        $request->session()->forget('auth');
        
        // Regenerate session ID
        $request->session()->regenerate();

        return redirect('/login');
    }
}
