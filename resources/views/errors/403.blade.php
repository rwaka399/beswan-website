<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-white min-h-screen flex items-center justify-center p-4">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-gray-900 mb-4">403</h1>
        <p class="text-xl text-gray-600 mb-2">Akses Ditolak</p>
        <p class="text-gray-500 mb-6">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
        
        @auth
            <p class="text-sm text-gray-400 mb-4">
                {{ Auth::user()->name }}
                @if(Auth::guard('web') instanceof \App\Guards\CustomSessionGuard)
                    @php $userRole = Auth::guard('web')->getUserRole(); @endphp
                    @if($userRole) - {{ $userRole['role_name'] }} @endif
                @endif
            </p>
        @endauth

        <div class="space-x-4">
            <a href="javascript:history.back()" class="text-blue-600 hover:underline">← Kembali</a>
            <span class="text-gray-300">|</span>
            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
        </div>
    </div>
</body>
</html>
