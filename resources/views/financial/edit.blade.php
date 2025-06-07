@extends('master.layout')

@section('title', 'Edit Log Keuangan')

@section('content')
    <div class="mt-4 max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Log Keuangan</h1>
            <a href="{{ route('financial-show', $financialLog->financial_log_id) }}"
                class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition duration-300">
                Kembali
            </a>
        </div>

        <!-- Flash Messages -->
        @if (session('error'))
            <div class="flash-message transition-opacity duration-500 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6"
                role="alert">
                {{ session('error') }}
            </div>
        @endif

        <!-- Form -->
        <div class="bg-white shadow-sm rounded-lg p-6">
            <form action="{{ route('financial-update', $financialLog->financial_log_id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Transaction Date -->
                    <div>
                        <label for="transaction_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Transaksi <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="transaction_date" id="transaction_date" 
                            value="{{ old('transaction_date', $financialLog->transaction_date ? $financialLog->transaction_date->format('Y-m-d') : '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('transaction_date') border-red-500 @enderror">
                        @error('transaction_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>                    <!-- Financial Type -->
                    <div>
                        <label for="financial_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Transaksi <span class="text-red-500">*</span>
                        </label>
                        <select name="financial_type" id="financial_type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('financial_type') border-red-500 @enderror">
                            <option value="">Pilih Jenis Transaksi</option>
                            <option value="income" {{ old('financial_type', $financialLog->financial_type) == 'income' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="expense" {{ old('financial_type', $financialLog->financial_type) == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>
                        @error('financial_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="number" name="amount" id="amount" 
                                value="{{ old('amount', $financialLog->amount) }}" step="0.01" min="0"
                                placeholder="0"
                                class="w-full pl-12 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('amount') border-red-500 @enderror">
                        </div>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>                    <!-- Payment Method -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                            Metode Pembayaran
                        </label>
                        <select name="payment_method" id="payment_method"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('payment_method') border-red-500 @enderror">
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="cash" {{ old('payment_method', $financialLog->payment_method) == 'cash' ? 'selected' : '' }}>Tunai</option>
                            <option value="bank_transfer" {{ old('payment_method', $financialLog->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="credit_card" {{ old('payment_method', $financialLog->payment_method) == 'credit_card' ? 'selected' : '' }}>Kartu Kredit</option>
                            <option value="e_wallet" {{ old('payment_method', $financialLog->payment_method) == 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- User -->
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                            User <span class="text-red-500">*</span>
                        </label>
                        <select name="user_id" id="user_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('user_id') border-red-500 @enderror">
                            <option value="">Pilih User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->user_id }}" {{ old('user_id', $financialLog->user_id) == $user->user_id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Invoice ID (Optional) -->
                    <div>
                        <label for="invoice_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Invoice ID (Opsional)
                        </label>
                        <input type="text" name="invoice_id" id="invoice_id" 
                            value="{{ old('invoice_id', $financialLog->invoice_id) }}"
                            placeholder="Masukkan Invoice ID jika ada"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('invoice_id') border-red-500 @enderror">
                        @error('invoice_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="4"
                        placeholder="Masukkan deskripsi transaksi..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $financialLog->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
                    <a href="{{ route('financial-show', $financialLog->financial_log_id) }}"
                        class="px-6 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 text-center">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">
                        Update Log Keuangan
                    </button>
                </div>
            </form>
        </div>

        <!-- Log History -->
        <div class="mt-6 bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Log</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                <div>
                    <span class="font-medium">Dibuat pada:</span>
                    {{ $financialLog->created_at ? $financialLog->created_at->format('d F Y H:i') : '-' }}
                </div>
                <div>
                    <span class="font-medium">Terakhir diupdate:</span>
                    {{ $financialLog->updated_at ? $financialLog->updated_at->format('d F Y H:i') : '-' }}
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide flash messages
        setTimeout(function() {
            const flashMessages = document.querySelectorAll('.flash-message');
            flashMessages.forEach(function(message) {
                message.style.opacity = '0';
                setTimeout(function() {
                    message.remove();
                }, 500);
            });
        }, 5000);

        // Format amount input
        document.getElementById('amount').addEventListener('input', function(e) {
            let value = e.target.value;
            // Remove any non-numeric characters except decimal point
            value = value.replace(/[^0-9.]/g, '');
            e.target.value = value;
        });
    </script>
@endsection