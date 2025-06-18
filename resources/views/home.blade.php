<!doctype html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BESWAN COURSE - Kursus Bahasa Inggris Terbaik</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    
    <!-- Additional Styles for Maps -->
    <style>
        /* Custom styles for map responsiveness */
        .aspect-w-16 {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
        }
        
        .aspect-w-16 iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        
        /* Custom animation for map markers */
        @keyframes pulse-marker {
            0%, 100% { 
                transform: scale(1);
                opacity: 1;
            }
            50% { 
                transform: scale(1.1);
                opacity: 0.8;
            }
        }
        
        .animate-pulse-marker {
            animation: pulse-marker 2s infinite;
        }
        
        /* Smooth transitions for buttons */
        .map-button {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .map-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        /* Custom scrollbar for mobile */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        
        /* Loading animation */
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>
</head>

<body class="font-sans bg-gray-50">

    <!-- Modern Navbar -->
    <nav class="fixed top-0 w-full bg-white/95 backdrop-blur-md shadow-sm z-50 border-b border-gray-100" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            BESWAN
                        </h1>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-8">
                        <a href="#home" class="nav-link text-gray-900 hover:text-blue-600 font-medium transition-colors">Beranda</a>
                        <a href="#tentang" class="nav-link text-gray-700 hover:text-blue-600 font-medium transition-colors">Tentang</a>
                        <a href="#paket" class="nav-link text-gray-700 hover:text-blue-600 font-medium transition-colors">Paket</a>
                        <a href="#pengajar" class="nav-link text-gray-700 hover:text-blue-600 font-medium transition-colors">Pengajar</a>
                        <a href="#kontak" class="nav-link text-gray-700 hover:text-blue-600 font-medium transition-colors">Kontak</a>
                        
                        @auth
                            @if(Auth::user()->isAdmin())
                                <!-- Admin Menu Dropdown -->
                                <div x-data="{ masterOpen: false }" class="relative">
                                    <button @click="masterOpen = !masterOpen" 
                                            class="flex items-center space-x-1 text-gray-700 hover:text-blue-600 font-medium transition-colors">
                                        <span>Master</span>
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                    
                                    <!-- Master Dropdown Menu -->
                                    <div x-show="masterOpen" @click.outside="masterOpen = false" x-transition
                                         class="absolute top-8 left-0 w-56 bg-white rounded-xl shadow-lg py-2 border border-gray-100 z-50">
                                        <a href="{{ route('dashboard') }}" 
                                           class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4"/>
                                            </svg>
                                            Dashboard
                                        </a>
                                        
                                        <a href="{{ route('user-index') }}" 
                                           class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                            </svg>
                                            Kelola User
                                        </a>
                                        <a href="{{ route('role-index') }}" 
                                           class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                            Kelola Role
                                        </a>
                                        <a href="{{ route('menu-index') }}" 
                                           class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                            </svg>
                                            Kelola Menu
                                        </a>
                                        
                                        <a href="{{ route('lesson-package-index') }}" 
                                           class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                            Paket Pelajaran
                                        </a>
                                        
                                        <a href="{{ route('financial-index') }}" 
                                           class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                            </svg>
                                            Keuangan
                                        </a>
                                        
                                        <a href="{{ route('master.attendance.index') }}" 
                                           class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012-2m-6 9l2 2 4-4"/>
                                            </svg>
                                            Absensi Master
                                        </a>
                                    </div>
                                </div>
                            @elseif(Auth::user()->hasRole('Guru'))
                                <!-- Guru Menu Dropdown -->
                                <div x-data="{ guruOpen: false }" class="relative">
                                    <button @click="guruOpen = !guruOpen" 
                                            class="flex items-center space-x-1 text-gray-700 hover:text-blue-600 font-medium transition-colors">
                                        <span>Guru</span>
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                    
                                    <!-- Guru Dropdown Menu -->
                                    <div x-show="guruOpen" @click.outside="guruOpen = false" x-transition
                                         class="absolute top-8 left-0 w-48 bg-white rounded-xl shadow-lg py-2 border border-gray-100 z-50">
                                        <a href="{{ route('teacher.attendance.index') }}" 
                                           class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012-2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                            </svg>
                                            Absensi Saya
                                        </a>
                                        <a href="{{ route('teacher.attendance.history') }}" 
                                           class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Riwayat Absensi
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Auth Section -->
                <div class="flex items-center space-x-4">
                    @guest
                        <a href="{{ route('login') }}" 
                           class="hidden md:block text-gray-700 hover:text-blue-600 font-medium transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" 
                           class="hidden md:block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg font-medium hover:shadow-lg transition-all duration-200">
                            Daftar
                        </a>
                    @endguest

                    @auth
                        <div x-data="{ open: false }" class="relative flex items-center gap-3">
                            <!-- Premium Badge -->
                            @if(Auth::user() && Auth::user()->isPremium())
                                <span class="hidden md:block bg-gradient-to-r from-yellow-400 to-amber-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                                    ⭐ Premium
                                </span>
                            @endif
                            
                            <!-- Profile Dropdown -->
                            <button @click="open = !open" class="hidden md:flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition-colors">
                                <img src="{{ Auth::user()->profile_picture ?? '/storage/profile.png' }}" 
                                     alt="Profile" class="w-8 h-8 rounded-full object-cover ring-2 ring-gray-200">
                                <span class="hidden lg:block font-medium">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.outside="open = false" x-transition
                                 class="absolute right-0 top-12 w-48 bg-white rounded-xl shadow-lg py-2 border border-gray-100">
                                <a href="{{ route('profile-index') }}" 
                                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Profil Saya
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="flex items-center w-full px-4 py-2 text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth

                    <!-- Mobile menu button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-gray-100 transition-colors">
                        <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="mobileMenuOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t border-gray-100">
                <a href="#home" class="nav-link block px-3 py-2 text-gray-900 hover:text-blue-600 font-medium transition-colors" @click="mobileMenuOpen = false">Beranda</a>
                <a href="#tentang" class="nav-link block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition-colors" @click="mobileMenuOpen = false">Tentang</a>
                <a href="#paket" class="nav-link block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition-colors" @click="mobileMenuOpen = false">Paket</a>
                <a href="#pengajar" class="nav-link block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition-colors" @click="mobileMenuOpen = false">Pengajar</a>
                <a href="#kontak" class="nav-link block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition-colors" @click="mobileMenuOpen = false">Kontak</a>
                
                @auth
                    @if(Auth::user()->isAdmin())
                        <!-- Admin Mobile Menu -->
                        <div class="border-t border-gray-100 pt-3 mt-3">
                            <div class="px-3 py-2">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Menu Master</span>
                            </div>
                            
                            <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition-colors" @click="mobileMenuOpen = false">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4"/>
                                </svg>
                                Dashboard
                            </a>
                            
                            <a href="{{ route('user-index') }}" class="flex items-center px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition-colors" @click="mobileMenuOpen = false">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                                Kelola User
                            </a>
                            <a href="{{ route('role-index') }}" class="flex items-center px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition-colors" @click="mobileMenuOpen = false">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                Kelola Role
                            </a>
                            <a href="{{ route('menu-index') }}" class="flex items-center px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition-colors" @click="mobileMenuOpen = false">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                </svg>
                                Kelola Menu
                            </a>
                            
                            <a href="{{ route('lesson-package-index') }}" class="flex items-center px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition-colors" @click="mobileMenuOpen = false">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                Paket Pelajaran
                            </a>
                            
                            <a href="{{ route('financial-index') }}" class="flex items-center px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition-colors" @click="mobileMenuOpen = false">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                                Keuangan
                            </a>
                            
                            <a href="{{ route('master.attendance.index') }}" class="flex items-center px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition-colors" @click="mobileMenuOpen = false">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012-2m-6 9l2 2 4-4"/>
                                </svg>
                                Absensi Master
                            </a>
                        </div>
                    @elseif(Auth::user()->hasRole('Guru'))
                        <!-- Guru Mobile Menu -->
                        <div class="border-t border-gray-100 pt-3 mt-3">
                            <div class="px-3 py-2">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Menu Guru</span>
                            </div>
                            <a href="{{ route('teacher.attendance.index') }}" class="flex items-center px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition-colors" @click="mobileMenuOpen = false">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012-2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                                Absensi Saya
                            </a>
                            <a href="{{ route('teacher.attendance.history') }}" class="flex items-center px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition-colors" @click="mobileMenuOpen = false">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Riwayat Absensi
                            </a>
                        </div>
                    @endif
                @endauth
                
                @guest
                    <div class="border-t border-gray-100 pt-3 mt-3">
                        <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="block px-3 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-medium hover:shadow-lg transition-all duration-200 mx-3 mt-2 text-center">Daftar</a>
                    </div>
                @endguest

                @auth
                    <div class="border-t border-gray-100 pt-3 mt-3">
                        @if(Auth::user() && Auth::user()->isPremium())
                            <div class="px-3 py-2">
                                <span class="bg-gradient-to-r from-yellow-400 to-amber-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                                    ⭐ Premium
                                </span>
                            </div>
                        @endif
                        <a href="{{ route('profile-index') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition-colors">Profil Saya</a>
                        <form method="POST" action="{{ route('logout') }}" class="px-3 py-2">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-700 font-medium transition-colors">Keluar</button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative min-h-screen flex items-center overflow-hidden">
        <!-- Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-blue-800 to-purple-900"></div>
        <div class="absolute inset-0 bg-black/20"></div>
        
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl animate-pulse delay-1000"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Text Content -->
                <div class="text-white">
                    <div class="space-y-6">
                        <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-sm font-medium">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                            Kursus Bahasa Inggris #1 di Indonesia
                        </div>
                        
                        <h1 class="text-5xl lg:text-7xl font-bold leading-tight">
                            <span class="block">Kuasai</span>
                            <span class="block bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent">Bahasa Inggris</span>
                            <span class="block">dengan Mudah</span>
                        </h1>
                        
                        <p class="text-xl text-blue-100 leading-relaxed max-w-lg">
                            Bergabunglah dengan lebih dari 1000+ siswa yang telah sukses menguasai bahasa Inggris bersama pengajar profesional kami.
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-4 pt-4">
                            <a href="#paket" 
                               class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                                Mulai Belajar Sekarang
                            </a>
                            <a href="#tentang" 
                               class="inline-flex items-center justify-center px-8 py-4 border-2 border-white/30 text-white font-semibold rounded-xl hover:bg-white/10 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Pelajari Lebih Lanjut
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Hero Image/Animation -->
                <div class="lg:text-right">
                    <div class="relative">
                        <div class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-sm rounded-3xl p-8 border border-white/20">
                            <div class="space-y-6">
                                <!-- Stats Cards -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/10">
                                        <div class="text-3xl font-bold text-yellow-400">1000+</div>
                                        <div class="text-sm text-blue-100">Siswa Lulus</div>
                                    </div>
                                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/10">
                                        <div class="text-3xl font-bold text-yellow-400">95%</div>
                                        <div class="text-sm text-blue-100">Tingkat Kelulusan</div>
                                    </div>
                                </div>
                                
                                <!-- Achievement Badge -->
                                <div class="bg-gradient-to-r from-yellow-400/20 to-orange-500/20 backdrop-blur-sm rounded-2xl p-6 border border-yellow-400/30">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-900" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-white font-bold">Sertifikat Resmi</div>
                                            <div class="text-blue-100 text-sm">Diakui Nasional</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2">
            <div class="animate-bounce">
                <svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
            </div>
        </div>
    </section>
    <!-- Stats Section -->
    <section class="relative -mt-20 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-2xl p-8 lg:p-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center group">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-4xl font-bold text-gray-900 mb-2">1000+</h3>
                        <p class="text-lg font-semibold text-gray-700 mb-1">Siswa Lulus</p>
                        <p class="text-sm text-gray-500">Siswa yang sukses menguasai bahasa Inggris</p>
                    </div>
                    
                    <div class="text-center group">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-4xl font-bold text-gray-900 mb-2">10+</h3>
                        <p class="text-lg font-semibold text-gray-700 mb-1">Tahun Pengalaman</p>
                        <p class="text-sm text-gray-500">Pengajar berpengalaman dan bersertifikat</p>
                    </div>
                    
                    <div class="text-center group">
                        <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-4xl font-bold text-gray-900 mb-2">95%</h3>
                        <p class="text-lg font-semibold text-gray-700 mb-1">Tingkat Keberhasilan</p>
                        <p class="text-sm text-gray-500">Siswa fasih berbicara dalam 3 bulan</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    Mengapa Memilih <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">BESWAN?</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Kami menawarkan pendekatan pembelajaran yang inovatif dan personal untuk memastikan setiap siswa mencapai tujuan mereka.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
                <!-- Feature 1 -->
                <div class="group bg-gradient-to-br from-blue-50 to-blue-100 rounded-3xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-200">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Pengajaran Profesional</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Tim pengajar berpengalaman dengan sertifikasi internasional yang siap membantu Anda mencapai target pembelajaran dengan metode yang telah terbukti efektif.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="group bg-gradient-to-br from-purple-50 to-purple-100 rounded-3xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-200">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Kelas Interaktif</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Pembelajaran yang engaging dengan teknologi modern, games edukatif, dan praktik langsung yang membuat belajar bahasa Inggris menjadi menyenangkan dan efektif.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="group bg-gradient-to-br from-yellow-50 to-orange-100 rounded-3xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-200">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Program Eksklusif</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Program wisata edukasi bulanan untuk praktik bahasa secara real, networking dengan sesama learner, dan pengalaman belajar yang tak terlupakan di luar kelas.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Teachers Section -->
    <section id="pengajar" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    Tim <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Pengajar</span> Profesional
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Belajar dari pengajar berpengalaman yang telah membantu ribuan siswa mencapai impian mereka dalam menguasai bahasa Inggris.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 max-w-4xl mx-auto">
                <!-- Teacher 1 -->
                <div class="group bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                    <div class="text-center">
                        <div class="relative mb-6">
                            <img class="w-32 h-32 rounded-full mx-auto object-cover ring-4 ring-blue-100 group-hover:ring-blue-200 transition-all duration-300" 
                                 src="https://randomuser.me/api/portraits/men/1.jpg" alt="Mr. Hadi">
                            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Mr. Hadi Santoso</h3>
                        <p class="text-blue-600 font-semibold mb-1">Senior English Instructor</p>
                        <p class="text-sm text-gray-500 mb-4">TESOL Certified • 10+ Years Experience</p>
                        
                        <p class="text-gray-600 leading-relaxed mb-6">
                            Spesialis dalam persiapan ujian TOEFL dan IELTS dengan track record 95% kelulusan. 
                            Berpengalaman mengajar lebih dari 500 siswa dari berbagai latar belakang.
                        </p>
                        
                        <div class="flex items-center justify-center space-x-4 text-sm">
                            <div class="flex items-center text-gray-600">
                                <svg class="w-4 h-4 mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                4.9/5 Rating
                            </div>
                            <div class="text-gray-400">•</div>
                            <div class="text-gray-600">500+ Siswa</div>
                        </div>
                    </div>
                </div>

                <!-- Teacher 2 -->
                <div class="group bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                    <div class="text-center">
                        <div class="relative mb-6">
                            <img class="w-32 h-32 rounded-full mx-auto object-cover ring-4 ring-purple-100 group-hover:ring-purple-200 transition-all duration-300" 
                                 src="https://randomuser.me/api/portraits/women/2.jpg" alt="Mrs. Nina">
                            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Mrs. Nina Wijaya</h3>
                        <p class="text-purple-600 font-semibold mb-1">Conversation Specialist</p>
                        <p class="text-sm text-gray-500 mb-4">Cambridge Certified • 8+ Years Experience</p>
                        
                        <p class="text-gray-600 leading-relaxed mb-6">
                            Ahli dalam speaking dan pronunciation dengan metode communicative approach. 
                            Membantu siswa meningkatkan confidence dalam berbicara bahasa Inggris.
                        </p>
                        
                        <div class="flex items-center justify-center space-x-4 text-sm">
                            <div class="flex items-center text-gray-600">
                                <svg class="w-4 h-4 mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                4.8/5 Rating
                            </div>
                            <div class="text-gray-400">•</div>
                            <div class="text-gray-600">350+ Siswa</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <section id="paket" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    Paket <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Pembelajaran</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                    Pilih paket yang sesuai dengan kebutuhan dan target pembelajaran Anda. Semua paket dilengkapi dengan sertifikat resmi.
                </p>
                
                @auth
                    @if(Auth::user() && Auth::user()->isPremium())
                        <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-400 to-amber-500 text-white font-bold rounded-2xl shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Status Premium Aktif
                        </div>
                        <div class="mt-4 bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200 rounded-xl p-4 max-w-lg mx-auto">
                            <p class="text-sm text-gray-700">
                                <strong>Sisa waktu premium:</strong> <span class="text-blue-600 font-bold">{{ Auth::user()->getRemainingPremiumDays() }} hari</span>
                            </p>
                            <p class="text-xs text-gray-600 mt-1">
                                💡 Beli paket di bawah untuk memperpanjang premium Anda
                            </p>
                        </div>
                    @endif
                @endauth
            </div>

            <!-- Packages Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($lessonPackages as $index => $package)
                    @php
                        $colors = [
                            ['from-blue-500', 'to-blue-600', 'bg-blue-50', 'border-blue-200', 'text-blue-600'],
                            ['from-purple-500', 'to-purple-600', 'bg-purple-50', 'border-purple-200', 'text-purple-600'],
                            ['from-green-500', 'to-green-600', 'bg-green-50', 'border-green-200', 'text-green-600']
                        ];
                        $color = $colors[$index % 3];
                    @endphp
                    
                    <div class="group relative bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 overflow-hidden border border-gray-100">
                        <!-- Popular Badge for middle package -->
                        @if($index === 1)
                            <div class="absolute top-6 right-6 z-10">
                                <span class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                    🔥 POPULER
                                </span>
                            </div>
                        @endif

                        <div class="p-8">
                            <!-- Icon -->
                            <div class="flex justify-center mb-6">
                                <div class="w-16 h-16 bg-gradient-to-br {{ $color[0] }} {{ $color[1] }} rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 006 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3-.512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Package Name -->
                            <h3 class="text-2xl font-bold text-gray-900 text-center mb-3">
                                {{ $package->lesson_package_name }}
                            </h3>

                            <!-- Description -->
                            <p class="text-gray-600 text-center text-sm mb-6 leading-relaxed">
                                {{ $package->lesson_package_description ?? 'Tingkatkan kemampuan bahasa Inggris Anda dengan program pembelajaran yang komprehensif dan terstruktur.' }}
                            </p>

                            <!-- Duration Card -->
                            <div class="{{ $color[2] }} {{ $color[3] }} rounded-2xl p-4 mb-6">
                                <div class="text-center">
                                    <div class="flex items-center justify-center {{ $color[4] }} mb-2">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="font-semibold">Durasi Premium</span>
                                    </div>
                                    <div class="{{ $color[4] }} text-2xl font-bold mb-1">
                                        {{ $package->formatted_duration }}
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        {{ $package->duration_in_days }} hari akses penuh
                                    </div>
                                    
                                    @auth
                                        @if(Auth::user() && Auth::user()->isPremium())
                                            <div class="mt-3 p-2 bg-white/70 rounded-lg border border-white">
                                                <p class="text-xs {{ $color[4] }} font-medium">
                                                    ⚡ Perpanjangan otomatis setelah premium berakhir
                                                </p>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="text-center mb-6">
                                <div class="text-4xl font-bold text-gray-900 mb-1">
                                    Rp {{ number_format($package->lesson_package_price, 0, ',', '.') }}
                                </div>
                            </div>

                            <!-- Features -->
                            <div class="space-y-3 mb-8">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Akses semua materi pembelajaran
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Konsultasi dengan pengajar
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Sertifikat resmi
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Support 24/7
                                </div>
                            </div>

                            <!-- Button -->
                            @auth
                                @if(Auth::user() && Auth::user()->isPremium())
                                    <div class="space-y-3">
                                        <div class="text-center px-4 py-2 bg-green-100 text-green-800 font-semibold rounded-xl text-sm">
                                            ✓ Premium Aktif
                                        </div>
                                        <a href="{{ route('transaction.checkout', $package->lesson_package_id) }}"
                                           class="block w-full text-center px-6 py-3 bg-gradient-to-r {{ $color[0] }} {{ $color[1] }} text-white font-bold rounded-xl hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                            Perpanjang Premium
                                        </a>
                                    </div>
                                @else
                                    <a href="{{ route('transaction.checkout', $package->lesson_package_id) }}"
                                       class="block w-full text-center px-6 py-4 bg-gradient-to-r {{ $color[0] }} {{ $color[1] }} text-white font-bold rounded-xl hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                        Mulai Belajar Sekarang
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                   class="block w-full text-center px-6 py-4 bg-gradient-to-r {{ $color[0] }} {{ $color[1] }} text-white font-bold rounded-xl hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                    Login untuk Membeli
                                </a>
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="max-w-md mx-auto">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 006 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3-.512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Paket Segera Hadir</h3>
                            <p class="text-gray-600">Kami sedang menyiapkan paket pembelajaran terbaik untuk Anda.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-24 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    Pertanyaan <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Umum</span>
                </h2>
                <p class="text-xl text-gray-600">
                    Temukan jawaban atas pertanyaan yang sering diajukan tentang program pembelajaran kami.
                </p>
            </div>

            <div class="space-y-6">
                <div class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">Q</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Apa saja program bahasa yang tersedia?</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Kami fokus pada program Bahasa Inggris dengan berbagai level mulai dari Basic, Intermediate, hingga Advanced. 
                                Program dirancang khusus untuk conversation, TOEFL/IELTS preparation, dan Business English.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">Q</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Berapa lama durasi program pembelajaran?</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Durasi program bervariasi sesuai paket yang dipilih, mulai dari 1 bulan hingga 6 bulan. 
                                Setiap paket dirancang dengan intensitas yang optimal untuk hasil maksimal sesuai target Anda.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">Q</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Apakah tersedia kelas online?</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Ya! Kami menyediakan fleksibilitas dengan kelas online dan offline. 
                                Platform online kami dilengkapi dengan fitur interaktif dan materi digital yang dapat diakses 24/7.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">Q</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Bagaimana cara mendaftar?</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Pendaftaran sangat mudah! Cukup pilih paket yang sesuai, lakukan pembayaran online, 
                                dan Anda akan mendapat akses langsung ke platform pembelajaran kami.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-12">
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-8 border border-blue-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Masih ada pertanyaan?</h3>
                    <p class="text-gray-600 mb-4">Tim customer service kami siap membantu anda</p>
                    <a href="#kontak" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:shadow-lg transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="kontak" class="py-24 bg-gradient-to-br from-blue-900 via-blue-800 to-purple-900 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-0 left-0 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h2 class="text-4xl lg:text-6xl font-bold text-white mb-4">
                    Siap Mulai <span class="bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent">Perjalanan</span> Belajar?
                </h2>
                <p class="text-xl text-blue-100 max-w-2xl mx-auto leading-relaxed">
                    Bergabunglah dengan ribuan siswa yang telah merasakan pembelajaran bahasa Inggris yang menyenangkan dan efektif bersama kami.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                <a href="#paket" 
                   class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold text-lg rounded-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-200">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                    Daftar Sekarang
                </a>
                <a href="https://wa.me/6281217130420" 
                   class="inline-flex items-center justify-center px-8 py-4 border-2 border-white/30 text-white font-semibold text-lg rounded-xl hover:bg-white/10 transition-all duration-200">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    Konsultasi Gratis
                </a>
            </div>

            <!-- Contact Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                    <svg class="w-8 h-8 text-yellow-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <h3 class="text-white font-semibold mb-1">Alamat</h3>
                    <p class="text-blue-100 text-sm">Jl. Mojo No.105, Tertek, Tertek, Kec. Pare, Kabupaten Kediri, Jawa Timur 64215</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                    <svg class="w-8 h-8 text-yellow-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <h3 class="text-white font-semibold mb-1">Telepon</h3>
                    <p class="text-blue-100 text-sm">+62 81 217 130 420</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                    <svg class="w-8 h-8 text-yellow-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="text-white font-semibold mb-1">Email</h3>
                    <p class="text-blue-100 text-sm">info@beswan.com<br>support@beswan.com</p>
                </div>
            </div>

            <!-- Lokasi Section -->
            <div class="mt-16">
                <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20 max-w-6xl mx-auto">
                    <div class="text-center mb-8">
                        <h3 class="text-3xl font-bold text-white mb-4">📍 Lokasi Kami</h3>
                        <p class="text-blue-100 text-lg">Temukan kami di Kampung Inggris Pare, Kediri</p>
                    </div>
                    
                    <!-- Map Container - Full Width -->
                    <div class="w-full">
                        <!-- Google Maps Embed - Full Width -->
                        <div class="relative rounded-2xl overflow-hidden shadow-2xl mb-8">
                            <div class="bg-gray-200 rounded-2xl overflow-hidden">
                                <iframe 
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.9!2d112.19!3d-7.76!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e785963b1234567%3A0x123456789abcdef0!2sJl.%20Mojo%20No.105%2C%20Tertek%2C%20Kec.%20Pare%2C%20Kabupaten%20Kediri%2C%20Jawa%20Timur%2064215!5e0!3m2!1sen!2sid!4v1703123456789!5m2!1sen!2sid"
                                    width="100%" 
                                    height="450" 
                                    style="border:0;" 
                                    allowfullscreen="" 
                                    loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade"
                                    class="rounded-2xl">
                                </iframe>
                            </div>
                            
                            <!-- Simple Map Overlay -->
                            <div class="absolute top-4 left-4">
                                <div class="bg-white/95 backdrop-blur-sm rounded-lg px-4 py-2 shadow-lg">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                                        <span class="text-gray-800 font-semibold text-sm">BESWAN Course</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Location Info - Centered & Simplified -->
                        <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10 text-center">
                            <div class="mb-6">
                                <h4 class="text-2xl font-bold text-white mb-4 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Alamat Lengkap
                                </h4>
                                <div class="text-blue-100 text-xl leading-relaxed mb-8">
                                    <p class="font-semibold text-2xl text-white mb-2">Jl. Mojo No.105, Tertek, Tertek</p>
                                    <p class="text-lg">Kec. Pare, Kabupaten Kediri</p>
                                    <p class="text-lg">Jawa Timur 64215, Indonesia</p>
                                </div>
                            </div>
                            
                            <!-- Action Buttons - Horizontal Layout -->
                            <div class="flex flex-col sm:flex-row gap-4 justify-center max-w-2xl mx-auto">
                                <a href="https://maps.google.com/?q=Jl.+Mojo+No.105,+Tertek,+Kec.+Pare,+Kabupaten+Kediri,+Jawa+Timur+64215" 
                                   target="_blank"
                                   class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl transition-all duration-200 shadow-lg text-lg font-semibold">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"/>
                                    </svg>
                                    Buka di Google Maps
                                </a>
                                
                                <button onclick="copyAddress()" 
                                        class="flex items-center justify-center px-6 py-4 bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded-xl transition-all duration-200 text-lg font-semibold">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    Salin Alamat
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h3 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent mb-4">
                    BESWAN COURSE
                </h3>
                <p class="text-gray-400 mb-6">Wujudkan Impian Berbahasa Inggris Anda Bersama Kami</p>
                
                <div class="flex justify-center space-x-6 mb-8">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.097.118.112.222.085.343-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.001 12.017z"/>
                        </svg>
                    </a>
                </div>
                
                <div class="border-t border-gray-800 pt-6">
                    <p class="text-gray-400 text-sm">
                        © 2025 BESWAN COURSE. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Smooth Scroll Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scroll untuk semua link navigasi
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    
                    if (targetElement) {
                        const navHeight = 64; // tinggi navbar fixed
                        const targetPosition = targetElement.offsetTop - navHeight;
                        
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Update active navbar link saat scroll
            const sections = document.querySelectorAll('section[id]');

            window.addEventListener('scroll', function() {
                let current = '';
                const scrollPosition = window.pageYOffset + 100;

                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    
                    if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                        current = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('text-gray-900');
                    link.classList.add('text-gray-700');
                    
                    if (link.getAttribute('href') === `#${current}`) {
                        link.classList.remove('text-gray-700');
                        link.classList.add('text-gray-900');
                    }
                });
            });
        });
    </script>

    <!-- Simple Address Copy Script -->
    <script>
        // Alamat BESWAN Course
        const beswanAddress = "Jl. Mojo No.105, Tertek, Kec. Pare, Kabupaten Kediri, Jawa Timur 64215";

        // Fungsi untuk menyalin alamat
        function copyAddress() {
            navigator.clipboard.writeText(beswanAddress).then(function() {
                alert('✅ Alamat berhasil disalin!');
            }).catch(function() {
                // Fallback untuk browser lama
                const textArea = document.createElement('textarea');
                textArea.value = beswanAddress;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert('✅ Alamat berhasil disalin!');
            });
        }
    </script>

        // Fungsi untuk menyalin alamat
        function copyAddress() {
            navigator.clipboard.writeText(beswanLocation.address).then(function() {
                // Buat notifikasi
                showNotification('Alamat berhasil disalin ke clipboard!', 'success');
            }, function(err) {
                showNotification('Gagal menyalin alamat. Silakan coba lagi.', 'error');
            });
        }

        // Fungsi untuk mendapatkan lokasi pengguna dan menghitung jarak
        function calculateDistance() {
            if (navigator.geolocation) {
                const distanceInfo = document.getElementById('distanceInfo');
                distanceInfo.innerHTML = '<p class="text-blue-100">📍 Mendapatkan lokasi Anda...</p>';
                
                navigator.geolocation.getCurrentPosition(function(position) {
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;
                    
                    // Hitung jarak menggunakan formula Haversine
                    const distance = calculateDistanceHaversine(userLat, userLng, beswanLocation.lat, beswanLocation.lng);
                    
                    // Estimasi waktu tempuh (asumsi kecepatan rata-rata 40 km/jam)
                    const travelTime = Math.round((distance / 40) * 60); // dalam menit
                    
                    distanceInfo.innerHTML = `
                        <div class="space-y-3">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-white font-semibold">Jarak: ${distance.toFixed(1)} km</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-blue-100">Estimasi: ~${travelTime} menit berkendara</span>
                            </div>
                            <button onclick="openDirections(${userLat}, ${userLng})" 
                                    class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                🧭 Lihat Rute Perjalanan
                            </button>
                        </div>
                    `;
                }, function(error) {
                    let errorMessage = 'Tidak dapat mengakses lokasi Anda.';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage = 'Akses lokasi ditolak. Silakan aktifkan GPS dan izinkan akses lokasi.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage = 'Informasi lokasi tidak tersedia.';
                            break;
                        case error.TIMEOUT:
                            errorMessage = 'Timeout mendapatkan lokasi.';
                            break;
                    }
                    
                    distanceInfo.innerHTML = `
                        <div class="space-y-3">
                            <p class="text-red-300">❌ ${errorMessage}</p>
                            <button onclick="calculateDistance()" 
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                                🔄 Coba Lagi
                            </button>
                        </div>
                    `;
                });
            } else {
                document.getElementById('distanceInfo').innerHTML = 
                    '<p class="text-red-300">❌ Browser Anda tidak mendukung Geolocation.</p>';
            }
        }

        // Fungsi untuk membuka directions di Google Maps
        function openDirections(userLat, userLng) {
            const url = `https://www.google.com/maps/dir/${userLat},${userLng}/${beswanLocation.lat},${beswanLocation.lng}`;
            window.open(url, '_blank');
        }

        // Fungsi untuk mendapatkan lokasi pengguna saat ini
        function getCurrentLocation() {
            if (navigator.geolocation) {
                showNotification('📍 Mendapatkan lokasi Anda...', 'info');
                
                navigator.geolocation.getCurrentPosition(function(position) {
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;
                    
                    showNotification('✅ Lokasi berhasil didapat!', 'success');
                    
                    // Buka Google Maps dengan lokasi pengguna
                    const url = `https://www.google.com/maps/@${userLat},${userLng},15z`;
                    window.open(url, '_blank');
                }, function(error) {
                    showNotification('❌ Gagal mendapatkan lokasi Anda.', 'error');
                });
            } else {
                showNotification('❌ Browser tidak mendukung Geolocation.', 'error');
            }
        }

        // Formula Haversine untuk menghitung jarak antara dua koordinat
        function calculateDistanceHaversine(lat1, lng1, lat2, lng2) {
            const R = 6371; // Radius bumi dalam kilometer
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLng = (lng2 - lng1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLng/2) * Math.sin(dLng/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        }

        // Fungsi untuk menampilkan notifikasi
        function showNotification(message, type = 'info') {
            // Hapus notifikasi sebelumnya jika ada
            const existingNotification = document.getElementById('mapNotification');
            if (existingNotification) {
                existingNotification.remove();
            }

            // Buat elemen notifikasi
            const notification = document.createElement('div');
            notification.id = 'mapNotification';
            notification.className = `fixed top-20 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
            
            // Tentukan warna berdasarkan type
            let bgColor = 'bg-blue-600';
            if (type === 'success') bgColor = 'bg-green-600';
            else if (type === 'error') bgColor = 'bg-red-600';
            else if (type === 'warning') bgColor = 'bg-yellow-600';
            
            notification.className += ` ${bgColor} text-white`;
            notification.innerHTML = `
                <div class="flex items-center space-x-2">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;

            // Tambahkan ke body
            document.body.appendChild(notification);

            // Animasi slide in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Hapus otomatis setelah 4 detik
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (notification.parentElement) {
                            notification.remove();
                        }
                    }, 300);
                }
            }, 4000);
        }

        // Event listener untuk smooth scroll ke section kontak saat klik menu
        document.addEventListener('DOMContentLoaded', function() {
            const contactLinks = document.querySelectorAll('a[href="#kontak"]');
            contactLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById('kontak').scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                });
            });
        });
    </script>

</body>
</html>
