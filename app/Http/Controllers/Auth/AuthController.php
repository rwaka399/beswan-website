<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
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

    public function register()
    {
        $response = Http::get('https://ibnux.github.io/data-indonesia/provinsi.json');
        if ($response->failed()) {
            Log::error('Gagal mengambil data provinsi dari API');
            return view('auth.register', ['provinces' => []])->with('error', 'Failed to load provinces. Please try again later.');
        }

        $provinces = $response->json();
        return view('auth.register', compact('provinces'));
    }

    public function registerPost(Request $request)
    {
        // Debugging: Cetak data yang diterima
        Log::info('Data registrasi:', $request->all());

        // Validasi input dengan aturan tambahan
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'phone_number' => 'required|string|max:15|regex:/^\+?[1-9]\d{1,14}$/',
            'province' => 'required|string|max:100|not_in:Pilih Provinsi',
            'city' => 'required|string|max:100|not_in:Pilih Kota',
            'kecamatan' => 'required|string|max:100|not_in:Pilih Kecamatan',
            'address' => 'required|string|max:500',
        ], [
            'password.confirmed' => 'The password confirmation does not match.',
            'phone_number.regex' => 'The phone number format is invalid (e.g., +628123456789).',
            'province.not_in' => 'Please select a valid province.',
            'city.not_in' => 'Please select a valid city.',
            'kecamatan.not_in' => 'Please select a valid district.',
        ]);

        DB::beginTransaction();

        try {
            // Membuat user baru
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'province' => $request->province,
                'city' => $request->city,
                'kecamatan' => $request->kecamatan,
                'address' => $request->address,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

            // Ambil role 'User' dan assign ke user
            $role = Role::where('role_name', 'User')->first();
            if (!$role) {
                Log::error('Role User tidak ditemukan');
                throw new \Exception('Role User tidak ditemukan');
            }

            UserRole::create([
                'user_id' => $user->user_id,
                'role_id' => $role->role_id,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

            DB::commit();

            // Login dan set session
            Auth::login($user);
            $userRole = $user->userRoles()->first();
            Session::put([
                'role_id' => $userRole->role_id,
                'user_id' => $user->user_id,
            ]);

            // User baru otomatis role "User", redirect ke home
            return redirect()->intended('/')->with('success', 'Registration successful! Welcome to the platform.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registrasi gagal: ' . $e->getMessage());
            return back()->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Logout successful! See you next time.');
    }

    // ==================== SSO METHODS ====================
    
    public function redirectToProvider($provider)
    {
        // Validasi provider yang didukung
        $supportedProviders = ['google', 'facebook', 'github', 'twitter'];
        
        if (!in_array($provider, $supportedProviders)) {
            Log::error('Unsupported SSO provider', ['provider' => $provider]);
            return redirect('/login')->with('error', 'Provider SSO tidak didukung.');
        }

        try {
            // Check configuration before attempting redirect
            $config = config("services.{$provider}");
            if (!$config || !$config['client_id'] || !$config['client_secret']) {
                Log::error('SSO Configuration missing', [
                    'provider' => $provider,
                    'config_exists' => !empty($config),
                    'client_id_exists' => !empty($config['client_id'] ?? null),
                    'client_secret_exists' => !empty($config['client_secret'] ?? null),
                    'redirect_exists' => !empty($config['redirect'] ?? null),
                ]);
                
                return redirect('/login')->with('error', 'Konfigurasi ' . ucfirst($provider) . ' belum lengkap. Silakan hubungi administrator.');
            }

            Log::info('Redirecting to SSO provider', [
                'provider' => $provider,
                'redirect_url' => $config['redirect'] ?? 'not_set',
            ]);
            
            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            Log::error('SSO Redirect failed', [
                'provider' => $provider,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'config' => config("services.{$provider}", 'not_found')
            ]);
            
            return redirect('/login')->with('error', 'Gagal mengakses ' . ucfirst($provider) . '. Silakan coba lagi atau gunakan login biasa. Error: ' . $e->getMessage());
        }
    }

    public function handleProviderCallback($provider)
    {
        try {
            Log::info('Handling SSO callback', ['provider' => $provider]);
            
            $socialUser = Socialite::driver($provider)->user();
            
            if (!$socialUser || !$socialUser->getEmail()) {
                Log::error('Invalid social user data', ['provider' => $provider]);
                return redirect('/login')->with('error', 'Data pengguna dari ' . ucfirst($provider) . ' tidak valid.');
            }
            
            $user = $this->findOrCreateUser($socialUser, $provider);
            
            // Login menggunakan CustomSessionGuard yang sudah ada
            Auth::login($user);
            
            Log::info('SSO Login successful', [
                'user_id' => $user->user_id,
                'provider' => $provider,
                'email' => $user->email
            ]);
            
            return redirect()->intended('/')->with('success', 'Selamat datang kembali!');
            
        } catch (\Exception $e) {
            Log::error('SSO Login failed', [
                'provider' => $provider,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect('/login')->with('error', 'Login dengan ' . ucfirst($provider) . ' gagal. Silakan coba lagi atau gunakan login biasa.');
        }
    }

    private function findOrCreateUser($socialUser, $provider)
    {
        // Cari user berdasarkan email
        $user = User::where('email', $socialUser->getEmail())->first();
        
        if ($user) {
            // Update provider info jika user sudah ada
            $user->update([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'email_verified_at' => now(), // Auto verify dari SSO
            ]);
            
            return $user;
        }

        // Buat user baru jika belum ada
        DB::beginTransaction();
        
        try {
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'email_verified_at' => now(),
                'password' => bcrypt(str()->random(32)), // Random password
                'phone_number' => '', // Default empty, bisa diisi nanti
                'address' => '', // Default empty, bisa diisi nanti
            ]);

            // Assign role default "User"
            $defaultRole = Role::where('role_name', 'User')->first();
            if ($defaultRole) {
                UserRole::create([
                    'user_id' => $user->user_id,
                    'role_id' => $defaultRole->role_id,
                ]);
            }

            DB::commit();
            
            Log::info('New user created via SSO', [
                'user_id' => $user->user_id,
                'provider' => $provider
            ]);
            
            return $user;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
