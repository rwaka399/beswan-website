<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-red-50 to-orange-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Main Error Card -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 text-center transform hover:scale-105 transition-transform duration-300">
            <!-- Error Icon -->
            <div class="mb-6">
                <div class="mx-auto w-24 h-24 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-lock text-4xl text-red-500"></i>
                </div>
            </div>

            <!-- Error Code -->
            <h1 class="text-6xl font-bold text-red-500 mb-2">403</h1>
            
            <!-- Error Title -->
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Akses Ditolak</h2>
            
            <!-- Error Message -->
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 text-left">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            <strong>Maaf!</strong> Anda tidak memiliki izin untuk mengakses halaman ini.
                        </p>
                        @if(isset($exception) && $exception->getMessage())
                            <p class="text-xs text-red-600 mt-1">
                                {{ $exception->getMessage() }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- User Info -->
            @auth
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-center mb-2">
                        <i class="fas fa-user-circle text-blue-500 mr-2"></i>
                        <span class="text-sm font-medium text-blue-800">
                            {{ Auth::user()->name ?? 'User' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-center">
                        <i class="fas fa-user-tag text-blue-400 mr-2"></i>
                        <span class="text-xs text-blue-600">
                            Role: {{ Auth::user()->getRoleName() ?? 'Tidak ada role' }}
                        </span>
                    </div>
                </div>
            @endauth

            <!-- Action Buttons -->
            <div class="space-y-3">
                @auth
                    @php
                        $userRole = Auth::user()->getRoleName();
                    @endphp
                    
                    @if($userRole === 'Admin')
                        <a href="{{ route('dashboard') }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-colors duration-200 inline-block">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Kembali ke Dashboard
                        </a>
                    @elseif($userRole === 'Guru')
                        <a href="{{ route('teacher.attendance.index') }}" 
                           class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-xl transition-colors duration-200 inline-block">
                            <i class="fas fa-clipboard-check mr-2"></i>
                            Ke Halaman Attendance
                        </a>
                    @else
                        <a href="{{ route('profile-index') }}" 
                           class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-xl transition-colors duration-200 inline-block">
                            <i class="fas fa-user mr-2"></i>
                            Ke Halaman Profile
                        </a>
                    @endif
                @endauth

                <a href="{{ route('home') }}" 
                   class="w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-6 rounded-xl transition-colors duration-200 inline-block">
                    <i class="fas fa-home mr-2"></i>
                    Kembali ke Beranda
                </a>

                @guest
                    <a href="{{ route('login') }}" 
                       class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-xl transition-colors duration-200 inline-block">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login
                    </a>
                @endguest
            </div>

            <!-- Help Text -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Jika Anda merasa ini adalah kesalahan, silakan hubungi administrator sistem.
                </p>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-6 text-center">
            <div class="bg-white bg-opacity-80 rounded-2xl p-4">
                <h3 class="font-semibold text-gray-800 mb-2">
                    <i class="fas fa-shield-alt text-blue-500 mr-2"></i>
                    Informasi Akses
                </h3>
                <div class="text-sm text-gray-600 space-y-1">
                    @auth
                        @php $userRole = Auth::user()->getRoleName(); @endphp
                        
                        @if($userRole === 'Admin')
                            <p><i class="fas fa-check text-green-500 mr-1"></i> Akses penuh ke semua halaman master</p>
                            <p><i class="fas fa-times text-red-500 mr-1"></i> Tidak dapat akses: History Transaksi, Attendance Guru</p>
                        @elseif($userRole === 'Guru')
                            <p><i class="fas fa-check text-green-500 mr-1"></i> Akses: Attendance, Profile</p>
                            <p><i class="fas fa-times text-red-500 mr-1"></i> Tidak dapat akses halaman master lainnya</p>
                        @else
                            <p><i class="fas fa-check text-green-500 mr-1"></i> Akses: Profile, History Transaksi</p>
                            <p><i class="fas fa-times text-red-500 mr-1"></i> Tidak dapat akses halaman master</p>
                        @endif
                    @else
                        <p><i class="fas fa-info text-blue-500 mr-1"></i> Silakan login untuk mengakses sistem</p>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Animation -->
    <div class="fixed top-0 left-0 w-full h-full pointer-events-none overflow-hidden -z-10">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-red-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-pulse"></div>
        <div class="absolute top-3/4 right-1/4 w-64 h-64 bg-orange-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-pulse animation-delay-2000"></div>
        <div class="absolute bottom-1/4 left-1/2 w-64 h-64 bg-yellow-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-pulse animation-delay-4000"></div>
    </div>

    <style>
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</body>
</html>
