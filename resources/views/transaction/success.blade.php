<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran Berhasil - Beswan Course</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes bounce-in {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); opacity: 1; }
        }
        
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .bounce-in { animation: bounce-in 0.6s ease-out; }
        .fade-in { animation: fade-in 0.5s ease-out; }
        .fade-in-delay { animation: fade-in 0.5s ease-out 0.2s both; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-100 to-green-100 min-h-screen flex items-center justify-center px-4 py-8">
    
    <!-- Main Success Card -->
    <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-8 text-center">
        <!-- Success Icon -->
        <div class="mb-6 bounce-in">
            <!-- Main Success Icon -->
            <div class="mx-auto w-16 h-16 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
            
            <!-- Title and Subtitle -->
            <h1 class="text-3xl font-bold text-green-600 mb-2">Pembayaran Berhasil!</h1>
            <p class="text-gray-600 text-lg mb-3">🎉 Selamat! Anda telah bergabung dengan Beswan Course</p>
        </div>

        @if ($invoice)
        <!-- Invoice Card -->
        <div class="mb-8 fade-in-delay">
            <div class="bg-white/90 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Detail Pembayaran</h2>
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                        @if(strtoupper($invoice->status) === 'PAID') 
                            ✓ Lunas
                        @elseif(strtoupper($invoice->status) === 'PENDING') 
                            ⏳ Menunggu
                        @else 
                            {{ $invoice->status }}
                        @endif
                    </span>
                </div>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nomor Invoice:</span>
                        <span class="font-semibold text-gray-800">#{{ $invoice->external_id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Paket:</span>
                        <span class="font-semibold text-gray-800">{{ $invoice->lessonPackage->lesson_package_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Durasi:</span>
                        <span class="font-semibold text-blue-600">{{ $invoice->lessonPackage->formatted_duration }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Bayar:</span>
                        <span class="font-bold text-green-600 text-lg">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal:</span>
                        <span class="font-semibold text-gray-800">{{ $invoice->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Premium Status Card -->
        @php
            $userPackages = auth()->user()->userLessonPackages()
                ->whereIn('status', ['active', 'scheduled'])
                ->where('end_date', '>', now())
                ->orderBy('end_date', 'desc')
                ->get();
            
            $activePackage = $userPackages->where('status', 'active')->first();
            $scheduledPackage = $userPackages->where('status', 'scheduled')->first();
        @endphp

        @if($activePackage)
        <!-- Active Premium Card -->
        <div class="mb-8 fade-in-delay">
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-6 border border-yellow-200">
                <div class="flex items-center justify-center mb-4">
                    <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-400 to-yellow-600 text-white font-semibold rounded-full shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        ⭐ Status Premium Aktif!
                    </div>
                </div>
                <p class="text-center text-gray-700 mb-4">
                    Selamat! Akun Anda telah berhasil di-upgrade ke Premium. Anda dapat mengakses semua materi pembelajaran yang tersedia.
                </p>
                
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-sm text-gray-500">Berlaku hingga</p>
                            <p class="font-bold text-orange-600">{{ $activePackage->end_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Sisa waktu</p>
                            <p class="font-bold text-blue-600">{{ now()->diffInDays($activePackage->end_date) }} hari</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total durasi</p>
                            <p class="font-bold text-green-600">{{ $activePackage->lessonPackage->formatted_duration }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @elseif($scheduledPackage)
        <!-- Scheduled Premium Card -->
        <div class="mb-8 fade-in-delay">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
                <div class="flex items-center justify-center mb-4">
                    <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-full shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        📅 Premium Terjadwal
                    </div>
                </div>
                <p class="text-center text-gray-700 mb-4">
                    Premium Anda akan aktif pada tanggal yang telah Anda pilih. Kami akan mengaktifkannya secara otomatis!
                </p>
                
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-sm text-gray-500">Mulai premium</p>
                            <p class="font-bold text-blue-600">{{ $scheduledPackage->scheduled_start_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Akan berakhir</p>
                            <p class="font-bold text-orange-600">{{ $scheduledPackage->end_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Durasi premium</p>
                            <p class="font-bold text-green-600">{{ $scheduledPackage->lessonPackage->formatted_duration }}</p>
                        </div>
                    </div>
                    
                    @if($scheduledPackage->scheduled_start_date->isToday())
                        <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center text-green-800">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">Premium akan aktif hari ini!</span>
                            </div>
                        </div>
                    @elseif($scheduledPackage->scheduled_start_date->isFuture())
                        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center text-blue-800">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">Menunggu {{ $scheduledPackage->scheduled_start_date->diffInDays(now()) }} hari lagi</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8 fade-in-delay">
            <a href="{{ route('history') ?? '#' }}" 
               class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg transition-all duration-200 hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Lihat Riwayat
            </a>
            
            <a href="{{ url('/') }}" 
               class="inline-flex items-center justify-center bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg transition-all duration-200 hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Kembali ke Beranda
            </a>
        </div>

        <!-- Information Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 fade-in-delay">
            <!-- Next Steps Card -->
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <h4 class="font-bold text-blue-800 mb-2">Langkah Selanjutnya</h4>
                        <p class="text-blue-700 text-sm">
                            Akses pembelajaran akan aktif dalam 24 jam. Detail akses akan dikirim ke email Anda.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Support Card -->
            <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <h4 class="font-bold text-purple-800 mb-2">Butuh Bantuan?</h4>
                        <p class="text-purple-700 text-sm">
                            Tim support kami siap membantu 24/7 untuk pertanyaan seputar pembelajaran.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Footer Message -->
        <div class="text-center">
            <div class="inline-flex items-center bg-green-50 text-green-800 px-4 py-2 rounded-lg border border-green-200">
                <span class="text-xl mr-2">🎓</span>
                <span class="font-semibold">Selamat datang di Beswan Course!</span>
                <span class="text-xl ml-2">🌟</span>
            </div>
        </div>
    </div>

</body>
</html>
