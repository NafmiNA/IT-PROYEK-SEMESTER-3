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
                
                Auth::login($user);
                session()->regenerate();
                
                return $this->redirectByRole($user);
            }
            
            // Check if user exists by email
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if ($user) {
                // Link Google account to existing user
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
                
                Auth::login($user);
                session()->regenerate();
                
                return $this->redirectByRole($user);
            }
            
            // Create new user
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => bcrypt(Str::random(24)), // Random password for security
                'email_verified_at' => now(),
                'role' => 'mahasiswa', // Default role
            ]);
            
            Auth::login($user);
            session()->regenerate();
            
            return $this->redirectByRole($user);
            
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['error' => 'Login dengan Google gagal. Silakan coba lagi.']);
        }
    }

    /**
     * Redirect user based on their role
     */
    protected function redirectByRole($user)
    {
        if ($user->role === 'admin') {
            return redirect('/admin');
        }
        
        if ($user->role === 'mahasiswa') {
            return redirect()->route('mahasiswa.dashboard');
        }
        
        if ($user->role === 'dosen') {
            return redirect()->route('dosen.dashboard');
        }
        
        return redirect('/dashboard');
    }
}
