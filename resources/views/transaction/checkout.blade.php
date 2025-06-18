<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout - {{ $package->lesson_package_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex items-center justify-center py-12 px-4">

    <div class="w-full max-w-xl bg-white shadow-2xl rounded-3xl p-10">
        <h1 class="text-3xl font-extrabold text-gray-800 mb-6">
            Checkout: <span class="text-blue-600">{{ $package->lesson_package_name }}</span>
        </h1>

        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-6">
            <p class="text-lg text-gray-700 mb-2">
                <strong>Harga:</strong> 
                <span class="text-green-600 font-semibold">
                    Rp {{ number_format($package->lesson_package_price, 0, ',', '.') }}
                </span>
            </p>
            <p class="text-lg text-gray-700 mb-2">
                <strong>Durasi:</strong> 
                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                    {{ $package->lesson_duration }} Minggu
                </span>
            </p>
            <p class="text-lg text-gray-700">
                <strong>Deskripsi:</strong> 
                <span class="text-gray-600">
                    {{ $package->lesson_package_description ?? 'Paket ini menawarkan pembelajaran bahasa Inggris yang interaktif dan terjangkau.' }}
                </span>
            </p>
        </div>

        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <form id="payment-form" method="POST" action="{{ route('transaction.create-invoice') }}" novalidate class="space-y-6">
            @csrf
            <input type="hidden" name="lesson_package_id" value="{{ $package->lesson_package_id }}">

            <div>
                <label for="email" class="block text-gray-700 font-semibold mb-1">Email</label>
                <input type="email" name="email" id="email"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ auth()->user()->email ?? '' }}" required>
                <p class="text-sm text-gray-500 mt-1">Email akan digunakan untuk notifikasi pembayaran.</p>
                @error('email')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>
            
            <div>
                <label for="scheduled_start_date" class="block text-gray-700 font-semibold mb-1">Tanggal Mulai</label>
                <input type="date" name="scheduled_start_date" id="scheduled_start_date"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    min="{{ $minDate }}" max="{{ $maxDate }}" value="{{ $minDate }}" required>
                <p class="text-sm text-gray-500 mt-1">Pilih tanggal kapan Anda ingin memulai paket. Status premium akan aktif mulai tanggal ini.</p>
                @error('scheduled_start_date')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>
            
            <div>
                <label for="schedule_notes" class="block text-gray-700 font-semibold mb-1">Catatan (Opsional)</label>
                <textarea name="schedule_notes" id="schedule_notes"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 h-20"
                    placeholder="Tambahkan catatan jika ada..."></textarea>
            </div>

            <!-- Payment Gateway Selection -->
            <div>
                <label class="block text-gray-700 font-semibold mb-3">Pilih Payment Gateway</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <!-- Xendit Option -->
                    <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 payment-gateway-option">
                        <input type="radio" name="payment_gateway" value="xendit" class="mr-3 text-blue-600" checked>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                <span class="text-white font-bold text-sm">X</span>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Xendit</div>
                                <div class="text-sm text-gray-500">Transfer Bank, E-Wallet, QRIS</div>
                            </div>
                        </div>
                    </label>

                    <!-- Midtrans Option -->
                    <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 payment-gateway-option">
                        <input type="radio" name="payment_gateway" value="midtrans" class="mr-3 text-green-600">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                                <span class="text-white font-bold text-sm">M</span>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Midtrans</div>
                                <div class="text-sm text-gray-500">Kartu Kredit, GoPay, ShopeePay</div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <div id="xendit-payment-methods">
                <label class="block text-gray-700 font-semibold mb-3">Pilih Metode Pembayaran</label>
                
                <!-- Bank Transfer Section -->
                <div class="mb-4 border border-gray-200 rounded-lg overflow-hidden">
                    <button type="button" onclick="toggleAccordion('bank-transfer')" 
                            class="w-full p-4 bg-gray-50 hover:bg-gray-100 flex items-center justify-between transition-colors">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="font-semibold text-gray-700">Bank Transfer</span>
                        </div>
                        <svg id="bank-transfer-icon" class="w-5 h-5 text-gray-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="bank-transfer-content" class="hidden p-4 bg-white">
                        <div class="grid grid-cols-2 gap-3">
                            @foreach (['MANDIRI', 'BCA', 'BNI', 'BRI'] as $bank)
                                <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="radio" name="payment_method" value="{{ $bank }}" required class="mr-3 text-blue-600">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center mr-3">
                                            <span class="text-xs font-bold text-blue-600">{{ substr($bank, 0, 3) }}</span>
                                        </div>
                                        <span class="font-medium text-gray-700">{{ $bank }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- E-Wallet Section -->
                <div class="mb-4 border border-gray-200 rounded-lg overflow-hidden">
                    <button type="button" onclick="toggleAccordion('e-wallet')" 
                            class="w-full p-4 bg-gray-50 hover:bg-gray-100 flex items-center justify-between transition-colors">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-semibold text-gray-700">E-Wallet</span>
                        </div>
                        <svg id="e-wallet-icon" class="w-5 h-5 text-gray-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="e-wallet-content" class="hidden p-4 bg-white">
                        <div class="grid grid-cols-2 gap-3">
                            @foreach (['OVO', 'DANA', 'GOPAY', 'SHOPEEPAY'] as $ewallet)
                                <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="radio" name="payment_method" value="{{ $ewallet }}" required class="mr-3 text-green-600">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-green-600 rounded flex items-center justify-center mr-3">
                                            <span class="text-xs font-bold text-white">{{ substr($ewallet, 0, 1) }}</span>
                                        </div>
                                        <span class="font-medium text-gray-700">{{ $ewallet }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- QRIS Section -->
                <div class="mb-4 border border-gray-200 rounded-lg overflow-hidden">
                    <button type="button" onclick="toggleAccordion('qris')" 
                            class="w-full p-4 bg-gray-50 hover:bg-gray-100 flex items-center justify-between transition-colors">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                            </svg>
                            <span class="font-semibold text-gray-700">QRIS</span>
                        </div>
                        <svg id="qris-icon" class="w-5 h-5 text-gray-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="qris-content" class="hidden p-4 bg-white">
                        <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                            <input type="radio" name="payment_method" value="QRIS" required class="mr-3 text-purple-600">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-r from-purple-400 to-purple-600 rounded flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M3 11h8V3H3v8zm2-6h4v4H5V5zM3 21h8v-8H3v8zm2-6h4v4H5v-4zM13 3v8h8V3h-8zm6 6h-4V5h4v4zM13 13h2v2h-2zM15 15h2v2h-2zM13 17h2v2h-2zM15 19h2v2h-2zM17 13h2v2h-2zM19 15h2v2h-2zM17 17h2v2h-2zM19 19h2v2h-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">QRIS</span>
                                    <p class="text-xs text-gray-500">Scan QR Code untuk pembayaran</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                @error('payment_method')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <!-- Midtrans Note -->
            <div id="midtrans-note" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-blue-800 font-medium">Menggunakan Midtrans</p>
                        <p class="text-xs text-blue-600">Anda akan diarahkan ke halaman pembayaran Midtrans untuk memilih metode pembayaran.</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between mt-8">
                <a href="{{ route('home') }}" class="text-gray-600 hover:underline text-sm">
                    ← Kembali ke Daftar Paket
                </a>

                <button type="submit" id="pay-button"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                    Bayar Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Status Check Modal -->
<div id="statusCheckModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md mx-4 w-full">
        <div class="text-center">
            <div class="mb-4">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-blue-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Menunggu Pembayaran</h3>
                <p class="text-gray-600 mb-4">Silakan selesaikan pembayaran Anda. Kami akan mengecek status pembayaran secara otomatis.</p>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                    <p class="text-sm text-blue-800">
                        <strong>Order ID:</strong> <span id="modalOrderId" class="font-mono"></span>
                    </p>
                </div>

                <div class="flex items-center justify-center space-x-2 text-sm text-gray-500">
                    <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                    <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    <span class="ml-2">Mengecek status pembayaran...</span>
                </div>
            </div>

            <div class="space-y-3">
                <button id="checkStatusBtn" onclick="checkPaymentStatus()" 
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 transition duration-200">
                    Cek Status Manual
                </button>
                <button onclick="closeStatusModal()" 
                        class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-lg font-medium hover:bg-gray-200 transition duration-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Midtrans Snap Script -->
    @if (config('services.midtrans.client_key'))
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    @endif

    <script>
        const paymentForm = document.getElementById('payment-form');
        const payButton = document.getElementById('pay-button');
        const xenditMethods = document.getElementById('xendit-payment-methods');
        const midtransNote = document.getElementById('midtrans-note');

        // Payment Gateway Selection Handler
        document.querySelectorAll('input[name="payment_gateway"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove selected state from all gateway options
                document.querySelectorAll('.payment-gateway-option').forEach(option => {
                    option.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50', 'ring-green-500', 'bg-green-50');
                });
                
                // Add selected state to current option
                const currentOption = this.closest('.payment-gateway-option');
                if (this.value === 'xendit') {
                    currentOption.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50');
                    xenditMethods.style.display = 'block';
                    midtransNote.classList.add('hidden');
                    
                    // Clear Xendit payment method requirement
                    document.querySelectorAll('input[name="payment_method"]').forEach(input => {
                        input.removeAttribute('required');
                    });
                    // Add required back to Xendit methods
                    document.querySelectorAll('#xendit-payment-methods input[name="payment_method"]').forEach(input => {
                        input.setAttribute('required', 'required');
                    });
                } else {
                    currentOption.classList.add('ring-2', 'ring-green-500', 'bg-green-50');
                    xenditMethods.style.display = 'none';
                    midtransNote.classList.remove('hidden');
                    
                    // Clear all payment method selections for Midtrans
                    document.querySelectorAll('input[name="payment_method"]').forEach(input => {
                        input.checked = false;
                        input.removeAttribute('required');
                    });
                }
            });
        });

        // Accordion Toggle Function
        function toggleAccordion(section) {
            const content = document.getElementById(section + '-content');
            const icon = document.getElementById(section + '-icon');
            
            // Close all other accordions
            ['bank-transfer', 'e-wallet', 'qris'].forEach(otherSection => {
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

        paymentForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const emailInput = document.getElementById('email');
            if (!emailInput.value || !emailInput.checkValidity()) {
                alert('Harap masukkan email yang valid.');
                return;
            }

            const paymentGateway = document.querySelector('input[name="payment_gateway"]:checked').value;
            
            // Check if payment method is selected for Xendit
            if (paymentGateway === 'xendit') {
                const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
                if (!paymentMethod) {
                    alert('Harap pilih metode pembayaran.');
                    return;
                }
            }

            payButton.disabled = true;
            payButton.textContent = 'Memproses...';
            
            const formData = new FormData(paymentForm);
                
            // Validasi tanggal mulai
            const startDateInput = document.getElementById('scheduled_start_date');
            if (!startDateInput.value) {
                alert('Harap pilih tanggal mulai.');
                payButton.disabled = false;
                payButton.textContent = 'Bayar Sekarang';
                return;
            }

            try {
                // Determine endpoint based on payment gateway
                const endpoint = paymentGateway === 'xendit' 
                    ? '{{ route("transaction.create-invoice") }}'
                    : '{{ route("midtrans.create-invoice") }}';

                const response = await fetch(endpoint, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                });

                const result = await response.json();

                if (paymentGateway === 'xendit') {
                    if (result.invoice_url) {
                        window.location.href = result.invoice_url;
                    } else {
                        alert(result.message + (result.error ? ': ' + result.error : ''));
                    }
                } else {
                    // Midtrans handling
                    if (result.status === 'success' && result.snap_token) {
                        snap.pay(result.snap_token, {
                            onSuccess: function(result) {
                                window.location.href = '{{ route("transaction.success") }}';
                            },
                            onPending: function(result) {
                                // Start status checking for pending payments
                                showStatusCheckModal(result.order_id);
                            },
                            onError: function(result) {
                                console.error('Payment error:', result);
                                alert('Pembayaran gagal. Silakan coba lagi.');
                            },
                            onClose: function() {
                                payButton.disabled = false;
                                payButton.textContent = 'Bayar Sekarang';
                            }
                        });
                    } else {
                        alert(result.message || 'Terjadi kesalahan saat memproses pembayaran');
                    }
                }
            } catch (error) {
                alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
                console.error('Fetch error:', error);
            } finally {
                if (paymentGateway === 'xendit') {
                    payButton.disabled = false;
                    payButton.textContent = 'Bayar Sekarang';
                }
            }
        });

        // Add visual feedback when payment method is selected
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove selected state from all labels
                document.querySelectorAll('input[name="payment_method"]').forEach(r => {
                    r.closest('label').classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50');
                });
                
                // Add selected state to current label
                if (this.checked) {
                    this.closest('label').classList.add('ring-2', 'ring-blue-500', 'bg-blue-50');
                }
            });
        });

        // Auto-open accordion when a payment method is selected
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    // Determine which section this radio belongs to
                    let section = '';
                    if (['MANDIRI', 'BCA', 'BNI', 'BRI'].includes(this.value)) {
                        section = 'bank-transfer';
                    } else if (['OVO', 'DANA', 'GOPAY', 'SHOPEEPAY'].includes(this.value)) {
                        section = 'e-wallet';
                    } else if (this.value === 'QRIS') {
                        section = 'qris';
                    }
                    
                    // Make sure the correct accordion is open
                    if (section) {
                        const content = document.getElementById(section + '-content');
                        const icon = document.getElementById(section + '-icon');
                        if (content.classList.contains('hidden')) {
                            toggleAccordion(section);
                        }
                    }
                }
            });
        });

        // Status checking functions
        let statusCheckInterval;
        let currentOrderId;

        function showStatusCheckModal(orderId) {
            currentOrderId = orderId;
            document.getElementById('modalOrderId').textContent = orderId;
            document.getElementById('statusCheckModal').classList.remove('hidden');
            
            // Start auto checking every 10 seconds
            statusCheckInterval = setInterval(checkPaymentStatus, 10000);
            
            // Check immediately
            setTimeout(checkPaymentStatus, 2000);
        }

        function closeStatusModal() {
            document.getElementById('statusCheckModal').classList.add('hidden');
            if (statusCheckInterval) {
                clearInterval(statusCheckInterval);
            }
            
            // Reset checkout form
            payButton.disabled = false;
            payButton.textContent = 'Bayar Sekarang';
        }

        async function checkPaymentStatus() {
            if (!currentOrderId) return;

            try {
                console.log('Checking payment status for order:', currentOrderId);
                
                const response = await fetch('{{ route("midtrans.check-status") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ order_id: currentOrderId })
                });

                const result = await response.json();
                console.log('Payment status response:', result);

                if (result.status === 'paid' || result.status === 'settlement') {
                    console.log('Payment confirmed as successful!');
                    clearInterval(statusCheckInterval);
                    closeStatusModal();
                    window.location.href = '{{ route("transaction.success") }}';
                } else if (result.status === 'failed' || result.status === 'expired') {
                    console.log('Payment failed or expired');
                    clearInterval(statusCheckInterval);
                    closeStatusModal();
                    alert('Pembayaran gagal atau expired. Silakan coba lagi.');
                    window.location.href = '{{ route("transaction.failed") }}';
                } else {
                    console.log('Payment still pending, status:', result.status);
                    // Update UI to show current status
                    updateStatusDisplay(result.status, result.message);
                }
                // If still pending, continue checking
            } catch (error) {
                console.error('Error checking payment status:', error);
                // Update UI to show error
                updateStatusDisplay('error', 'Error checking status: ' + error.message);
            }
        }

        function updateStatusDisplay(status, message) {
            const statusText = document.querySelector('#statusCheckModal .text-gray-500');
            if (statusText) {
                statusText.innerHTML = `
                    <div class="flex items-center justify-center space-x-2">
                        <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                        <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        <span class="ml-2">Status: ${status} - ${message}</span>
                    </div>
                `;
            }
        }
    </script>

</body>
</html>