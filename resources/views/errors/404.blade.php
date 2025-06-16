<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-white min-h-screen flex items-center justify-center p-4">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-gray-900 mb-4">404</h1>
        <p class="text-xl text-gray-600 mb-2">Halaman Tidak Ditemukan</p>
        <p class="text-gray-500 mb-6">Halaman yang Anda cari tidak dapat ditemukan.</p>

        <div class="space-x-4">
            <a href="javascript:history.back()" class="text-blue-600 hover:underline">← Kembali</a>
            <span class="text-gray-300">|</span>
            <a href="{{ route('home') }}" class="text-blue-600 hover:underline">Beranda</a>
        </div>
    </div>
</body>
</html>
