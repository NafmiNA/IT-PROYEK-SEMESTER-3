<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user exists by Google ID
            $user = User::where('google_id', $googleUser->getId())->first();
            
            if ($user) {
                // Update avatar if changed
                if ($googleUser->getAvatar() && $user->avatar !== $googleUser->getAvatar()) {
                    $user->update(['avatar' => $googleUser->getAvatar()]);
                }
                
                // Refresh user data to ensure we have latest role
                $user->refresh();
                
                // Clear any existing session before login
                request()->session()->flush();
                
                Auth::login($user, true);
                request()->session()->regenerate();
                
                return $this->redirectByRole($user);
            }
            
            // Check if user exists by email (case-insensitive)
            $user = User::whereRaw('LOWER(email) = ?', [strtolower($googleUser->getEmail())])->first();
            
            if ($user) {
                // Link Google account to existing user
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
                
                // Refresh user data
                $user->refresh();
                
                // Clear any existing session before login
                request()->session()->flush();
                
                Auth::login($user, true);
                request()->session()->regenerate();
                
                return $this->redirectByRole($user);
            }
            
            // Create new user
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => bcrypt(Str::random(24)),
                'email_verified_at' => now(),
                'role' => 'mahasiswa',
            ]);
            
            // Clear any existing session before login
            request()->session()->flush();
            
            Auth::login($user, true);
            request()->session()->regenerate();
            
            return $this->redirectByRole($user);
            
        } catch (\Exception $e) {
            \Log::error('Google SSO Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->withErrors(['error' => 'Login dengan Google gagal: ' . $e->getMessage()]);
        }
    }

    /**
     * Redirect user based on their role
     */
    protected function redirectByRole($user)
    {
        // Force refresh user to get latest role from database
        $user = User::find($user->id);
        
        \Log::info('Google SSO Redirect - User: ' . $user->email . ', Role: ' . $user->role);
        
        if ($user->role === 'admin') {
            return redirect('/admin');
        }
        
        if ($user->role === 'dosen') {
            return redirect()->route('dosen.dashboard');
        }
        
        if ($user->role === 'mahasiswa') {
            return redirect()->route('mahasiswa.dashboard');
        }
        
        // Fallback
        return redirect('/dashboard');
    }
}
