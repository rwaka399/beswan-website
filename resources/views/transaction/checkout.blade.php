<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout - {{ $package->lesson_package_name }}</title>
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
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex items-center justify-center py-12 px-4">

    <div class="w-full max-w-2xl bg-white shadow-2xl rounded-3xl p-8 bounce-in">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-800 mb-2">
                Checkout Paket Premium
            </h1>
            <p class="text-gray-600">{{ $package->lesson_package_name }}</p>
        </div>        <!-- Package Info Card -->
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200 rounded-2xl p-6 mb-8 fade-in">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="font-semibold text-gray-800 mb-3">Detail Paket</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Harga:</span>
                            <span class="font-bold text-green-600">
                                Rp {{ number_format($package->lesson_package_price, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Durasi:</span>
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                                {{ $package->formatted_duration }}
                            </span>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 mb-3">Periode Premium</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center text-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            <span><strong>Mulai:</strong> <span id="start-date-display">{{ Carbon\Carbon::parse($minDate)->format('d M Y') }}</span></span>
                        </div>
                        <div class="flex items-center text-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            <span><strong>Berakhir:</strong> <span id="end-date-display">{{ $package->getEndDate(Carbon\Carbon::parse($minDate))->format('d M Y') }}</span></span>
                        </div>
                    </div>
                </div>
            </div>
            
            @if($package->lesson_package_description)
            <div class="mt-4 pt-4 border-t border-blue-200">
                <p class="text-gray-700 text-sm">
                    <strong>Deskripsi:</strong> {{ $package->lesson_package_description }}
                </p>
            </div>
            @endif
        </div>

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <form id="payment-form" method="POST" action="{{ route('transaction.create-invoice') }}" novalidate class="space-y-6 fade-in-delay">
            @csrf
            <input type="hidden" name="lesson_package_id" value="{{ $package->lesson_package_id }}">

            <!-- Email -->
            <div>
                <label for="email" class="block text-gray-700 font-semibold mb-2">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                    Email Pembayaran
                </label>
                <input type="email" name="email" id="email"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                    value="{{ auth()->user()->email ?? '' }}" required>
                <p class="text-sm text-gray-500 mt-1">Email untuk menerima notifikasi pembayaran dan akses premium.</p>
                @error('email')
                    <span class="text-sm text-red-600 flex items-center mt-1">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </span>
                @enderror
            </div>
            
            <!-- Tanggal Mulai Premium -->
            <div>
                <label for="scheduled_start_date" class="block text-gray-700 font-semibold mb-2">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Tanggal Mulai Premium
                </label>
                <input type="date" name="scheduled_start_date" id="scheduled_start_date"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                    min="{{ $minDate }}" max="{{ $maxDate }}" value="{{ $minDate }}" required>
                
                <div class="mt-3 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-amber-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm text-amber-800 font-medium">Informasi Penting:</p>
                            <p class="text-sm text-amber-700 mt-1">
                                Status premium akan aktif mulai tanggal yang Anda pilih. Jika memilih tanggal hari ini, premium akan aktif segera setelah pembayaran berhasil.
                            </p>
                        </div>
                    </div>
                </div>
                
                @error('scheduled_start_date')
                    <span class="text-sm text-red-600 flex items-center mt-2">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </span>
                @enderror
            </div>
            
            <!-- Catatan -->
            <div>
                <label for="schedule_notes" class="block text-gray-700 font-semibold mb-2">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Catatan (Opsional)
                </label>
                <textarea name="schedule_notes" id="schedule_notes"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none transition-all"
                    rows="3" placeholder="Tambahkan catatan khusus jika ada..."></textarea>
            </div>

            <!-- Payment Methods -->
            <div>
                <label class="block text-gray-700 font-semibold mb-3">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Pilih Metode Pembayaran
                </label>
                
                <!-- Bank Transfer Section -->
                <div class="mb-4 border border-gray-200 rounded-xl overflow-hidden">
                    <button type="button" onclick="toggleAccordion('bank-transfer')" 
                            class="w-full p-4 bg-gray-50 hover:bg-gray-100 flex items-center justify-between transition-colors">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="font-semibold text-gray-700">Bank Transfer</span>
                        </div>
                        <svg id="bank-transfer-icon" class="w-5 h-5 text-gray-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="bank-transfer-content" class="hidden p-4 bg-white">
                        <div class="grid grid-cols-2 gap-3">
                            @foreach (['MANDIRI', 'BCA', 'BNI', 'BRI'] as $bank)
                                <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors group">
                                    <input type="radio" name="payment_method" value="{{ $bank }}" class="sr-only">
                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 group-hover:border-blue-500 mr-3 flex items-center justify-center">
                                        <div class="w-2 h-2 rounded-full bg-blue-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    </div>
                                    <span class="font-medium">{{ $bank }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-3">Transfer langsung ke rekening bank pilihan Anda</p>
                    </div>
                </div>

                <!-- E-Wallet Section -->
                <div class="mb-4 border border-gray-200 rounded-xl overflow-hidden">
                    <button type="button" onclick="toggleAccordion('e-wallet')" 
                            class="w-full p-4 bg-gray-50 hover:bg-gray-100 flex items-center justify-between transition-colors">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <span class="font-semibold text-gray-700">E-Wallet</span>
                        </div>
                        <svg id="e-wallet-icon" class="w-5 h-5 text-gray-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="e-wallet-content" class="hidden p-4 bg-white">
                        <div class="grid grid-cols-2 gap-3">
                            @foreach (['OVO', 'DANA', 'GOPAY', 'SHOPEEPAY'] as $wallet)
                                <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors group">
                                    <input type="radio" name="payment_method" value="{{ $wallet }}" class="sr-only">
                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 group-hover:border-green-500 mr-3 flex items-center justify-center">
                                        <div class="w-2 h-2 rounded-full bg-green-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    </div>
                                    <span class="font-medium">{{ $wallet }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-3">Bayar dengan dompet digital favorit Anda</p>
                    </div>
                </div>

                <!-- QRIS Section -->
                <div class="mb-4 border border-gray-200 rounded-xl overflow-hidden">
                    <label class="flex items-center p-4 bg-gray-50 hover:bg-gray-100 cursor-pointer transition-colors group">
                        <input type="radio" name="payment_method" value="QRIS" class="sr-only">
                        <div class="w-4 h-4 rounded-full border-2 border-gray-300 group-hover:border-purple-500 mr-3 flex items-center justify-center">
                            <div class="w-2 h-2 rounded-full bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                            <div>
                                <span class="font-semibold text-gray-700">QRIS</span>
                                <p class="text-xs text-gray-500">Scan QR Code untuk pembayaran cepat</p>
                            </div>
                        </div>
                    </label>
                </div>

                @error('payment_method')
                    <span class="text-sm text-red-600 flex items-center mt-2">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('home') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar Paket
                </a>

                <button type="submit" id="pay-button"
                    class="inline-flex items-center bg-gradient-to-r from-blue-500 to-purple-600 text-white px-8 py-3 rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all transform hover:scale-105 font-semibold shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Bayar Sekarang
                </button>
            </div>
        </form>
    </div>

    <!-- Loading Modal -->
    <div id="loadingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl p-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-blue-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Memproses Pembayaran</h3>
            <p class="text-gray-600">Harap tunggu, kami sedang memproses permintaan Anda...</p>
        </div>
    </div>

    <script>
        const paymentForm = document.getElementById('payment-form');
        const payButton = document.getElementById('pay-button');
        const loadingModal = document.getElementById('loadingModal');
        const packageDurationDays = {{ $package->duration_in_days }};

        // Update end date when start date changes
        document.getElementById('scheduled_start_date').addEventListener('change', function() {
            updateEndDate();
        });

        function updateEndDate() {
            const startDateInput = document.getElementById('scheduled_start_date');
            const startDateDisplay = document.getElementById('start-date-display');
            const endDateDisplay = document.getElementById('end-date-display');
            
            if (startDateInput.value) {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(startDate);
                endDate.setDate(startDate.getDate() + packageDurationDays);
                
                const startFormatted = startDate.toLocaleDateString('id-ID', { 
                    day: 'numeric', 
                    month: 'short', 
                    year: 'numeric' 
                });
                const endFormatted = endDate.toLocaleDateString('id-ID', { 
                    day: 'numeric', 
                    month: 'short', 
                    year: 'numeric' 
                });
                
                startDateDisplay.textContent = startFormatted;
                endDateDisplay.textContent = endFormatted;
            }
        }

        // Initialize end date on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateEndDate();
        });

        // Accordion toggle function
        function toggleAccordion(section) {
            const content = document.getElementById(section + '-content');
            const icon = document.getElementById(section + '-icon');
            
            // Close all other accordions
            ['bank-transfer', 'e-wallet'].forEach(otherSection => {
                if (otherSection !== section) {
                    const otherContent = document.getElementById(otherSection + '-content');
                    const otherIcon = document.getElementById(otherSection + '-icon');
                    if (otherContent && !otherContent.classList.contains('hidden')) {
                        otherContent.classList.add('hidden');
                        otherIcon.style.transform = 'rotate(0deg)';
                    }
                }
            });
            
            // Toggle current accordion
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }

        // Payment method selection with visual feedback
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove selected state from all labels
                document.querySelectorAll('input[name="payment_method"]').forEach(r => {
                    const label = r.closest('label');
                    label.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50');
                    const circle = label.querySelector('div > div');
                    if (circle) circle.classList.remove('opacity-100');
                });
                
                // Add selected state to current label
                if (this.checked) {
                    const label = this.closest('label');
                    label.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50');
                    const circle = label.querySelector('div > div');
                    if (circle) circle.classList.add('opacity-100');
                }
            });
        });

        // Form submission
        paymentForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            // Validation
            const emailInput = document.getElementById('email');
            if (!emailInput.value || !emailInput.checkValidity()) {
                alert('Harap masukkan email yang valid.');
                return;
            }

            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (!paymentMethod) {
                alert('Harap pilih metode pembayaran.');
                return;
            }

            const startDateInput = document.getElementById('scheduled_start_date');
            if (!startDateInput.value) {
                alert('Harap pilih tanggal mulai premium.');
                return;
            }

            // Show loading
            loadingModal.classList.remove('hidden');
            payButton.disabled = true;
            
            const formData = new FormData(paymentForm);

            try {
                const response = await fetch('{{ route("transaction.create-invoice") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                });

                const result = await response.json();

                if (result.invoice_url) {
                    window.location.href = result.invoice_url;
                } else {
                    loadingModal.classList.add('hidden');
                    alert(result.message + (result.error ? ': ' + result.error : ''));
                }
                
            } catch (error) {
                loadingModal.classList.add('hidden');
                alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
                console.error('Fetch error:', error);
            } finally {
                payButton.disabled = false;
            }
        });
    </script>
</body>
</html>
