<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-white min-h-screen flex items-center justify-center p-4">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-gray-900 mb-4">500</h1>
        <p class="text-xl text-gray-600 mb-2">Server Error</p>
        <p class="text-gray-500 mb-6">Terjadi kesalahan pada server. Silakan coba lagi nanti.</p>

        <div class="space-x-4">
            <a href="javascript:location.reload()" class="text-orange-600 hover:underline">🔄 Muat Ulang</a>
            <span class="text-gray-300">|</span>
            <a href="{{ route('home') }}" class="text-blue-600 hover:underline">Beranda</a>
        </div>
    </div>
</body>
</html>
