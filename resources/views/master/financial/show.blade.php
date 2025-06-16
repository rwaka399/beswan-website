@extends('master.layout')

@section('title', 'Detail Log Keuangan')

@section('content')
    <div class="mt-4 max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Detail Log Keuangan</h1>
            <div class="flex space-x-2">
                <a href="{{ route('financial-edit', $financialLog->financial_log_id) }}"
                    class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition duration-300">
                    Edit
                </a>
                <a href="{{ route('financial-index') }}"
                    class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition duration-300">
                    Kembali
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="flash-message transition-opacity duration-500 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6"
                role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Financial Log Details -->
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Transaction Date -->
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Tanggal Transaksi</h3>
                    <p class="text-lg text-gray-900">
                        {{ $financialLog->transaction_date ? $financialLog->transaction_date->format('d F Y') : '-' }}
                    </p>
                </div>                <!-- Financial Type -->
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Jenis Transaksi</h3>
                    <div class="text-lg">
                        @if($financialLog->financial_type == 'income')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                Pemasukan
                            </span>
                        @else
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                Pengeluaran
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Amount -->
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Jumlah</h3>
                    <p class="text-2xl font-bold">
                        @if($financialLog->financial_type == 'income')
                            <span class="text-green-600">+ Rp {{ number_format($financialLog->amount, 0, ',', '.') }}</span>
                        @else
                            <span class="text-red-600">- Rp {{ number_format($financialLog->amount, 0, ',', '.') }}</span>
                        @endif
                    </p>
                </div>

                <!-- Payment Method -->
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Metode Pembayaran</h3>
                    <p class="text-lg text-gray-900">
                        {{ ucfirst(str_replace('_', ' ', $financialLog->payment_method ?? '-')) }}
                    </p>
                </div>

                <!-- User -->
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">User</h3>
                    <p class="text-lg text-gray-900">
                        {{ $financialLog->user->name ?? '-' }}
                        @if($financialLog->user->email)
                            <span class="text-sm text-gray-500">({{ $financialLog->user->email }})</span>
                        @endif
                    </p>
                </div>

                <!-- Invoice ID -->
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Invoice ID</h3>
                    <p class="text-lg text-gray-900">
                        @if($financialLog->invoice_id)
                            <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $financialLog->invoice_id }}</span>
                        @else
                            -
                        @endif
                    </p>
                </div>

                <!-- Lesson Package -->
                @if($financialLog->lessonPackage)
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Paket Kursus</h3>
                    <p class="text-lg text-gray-900">
                        {{ $financialLog->lessonPackage->lesson_package_name ?? '-' }}
                        @if($financialLog->lessonPackage->lesson_package_price)
                            <span class="text-sm text-gray-500">
                                (Rp {{ number_format($financialLog->lessonPackage->lesson_package_price, 0, ',', '.') }})
                            </span>
                        @endif
                    </p>
                </div>
                @endif

                <!-- Created At -->
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Dibuat Pada</h3>
                    <p class="text-lg text-gray-900">
                        {{ $financialLog->created_at ? $financialLog->created_at->format('d F Y H:i') : '-' }}
                    </p>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6 border-b border-gray-200 pb-4">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Deskripsi</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-900 whitespace-pre-line">{{ $financialLog->description ?? '-' }}</p>
                </div>
            </div>

            <!-- Notes -->
            @if($financialLog->notes)
            <div class="mt-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Catatan</h3>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-gray-900 whitespace-pre-line">{{ $financialLog->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="mt-6 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
            <button type="button"
                class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-300"
                onclick="openDeleteModal({{ $financialLog->financial_log_id }}, '{{ addslashes($financialLog->description) }}')">
                Hapus Log
            </button>
            <a href="{{ route('financial-edit', $financialLog->financial_log_id) }}"
                class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 text-center transition duration-300">
                Edit Log
            </a>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg shadow-lg p-6 m-4 max-w-sm w-full">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Hapus</h2>
                <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus log keuangan ini?</p>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                        Batal
                    </button>
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(id, name) {
            document.getElementById('deleteForm').action = `/financial/destroy/${id}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

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
    </script>
@endsection