<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CustomAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Check if the guard is our custom guard and has claimed data
            $guard = Auth::guard('web');
            if ($guard instanceof \App\Guards\CustomSessionGuard) {
                $userData = $guard->getUserData();
                $userRole = $guard->getUserRole();
                $userMenus = $guard->getUserMenus();
                $userPermissions = $guard->getUserPermissions();
                  // You can log or debug the claimed data here
                Log::info('User logged in with claimed data:', [
                    'user' => $userData,
                    'role' => $userRole,
                    'menus_count' => count($userMenus),
                    'permissions_count' => count($userPermissions)
                ]);
            }

            return redirect()->intended(route('home'))->with('success', 'Login successful!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
