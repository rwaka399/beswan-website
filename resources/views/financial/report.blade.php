@extends('master.layout')

@section('title', 'Laporan Keuangan')

@section('content')
    <div class="mt-4 max-w-full mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Laporan Keuangan</h1>
            <div class="flex space-x-2">
                <a href="{{ route('financial-export') }}" 
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    Export CSV
                </a>
                <a href="{{ route('financial-index') }}"
                    class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    Kembali
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Income -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-green-800">Total Pemasukan</h3>
                        <p class="text-2xl font-bold text-green-900">
                            Rp {{ number_format($totalIncome, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Expense -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-red-800">Total Pengeluaran</h3>
                        <p class="text-2xl font-bold text-red-900">
                            Rp {{ number_format($totalExpense, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Net Income -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-blue-800">Pendapatan Bersih</h3>
                        <p class="text-2xl font-bold {{ ($totalIncome - $totalExpense) >= 0 ? 'text-green-900' : 'text-red-900' }}">
                            Rp {{ number_format($totalIncome - $totalExpense, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Transactions -->
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-purple-800">Total Transaksi</h3>
                        <p class="text-2xl font-bold text-purple-900">
                            {{ $totalTransactions }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Monthly Chart -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Tren Bulanan</h2>
                <div class="h-64">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            <!-- Payment Method Chart -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Metode Pembayaran</h2>
                <div class="h-64">
                    <canvas id="paymentMethodChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Breakdown -->
        <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Per Bulan</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bulan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemasukan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengeluaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pendapatan Bersih</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">                        @forelse($monthlyData as $data)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $data->month_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                Rp {{ number_format($data->income, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                Rp {{ number_format($data->expense, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ ($data->income - $data->expense) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                Rp {{ number_format($data->income - $data->expense, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $data->transactions }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                Tidak ada data untuk ditampilkan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Packages -->
        @if(count($topPackages) > 0)
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Paket Kursus Terpopuler</h2>
            <div class="space-y-4">
                @foreach($topPackages as $package)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h3 class="font-medium text-gray-900">{{ $package->lesson_package_name }}</h3>
                        <p class="text-sm text-gray-500">{{ $package->transactions_count }} transaksi</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">
                            Rp {{ number_format($package->total_revenue, 0, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-500">Total Revenue</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>        // Monthly Chart
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyChart = new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: @json($monthlyData->pluck('month_name')),
                datasets: [{
                    label: 'Pemasukan',
                    data: @json($monthlyData->pluck('income')),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Pengeluaran',
                    data: @json($monthlyData->pluck('expense')),
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });        // Payment Method Chart
        const paymentCtx = document.getElementById('paymentMethodChart').getContext('2d');
        const paymentMethods = @json($paymentMethodStats->pluck('payment_method'));
        const paymentCounts = @json($paymentMethodStats->pluck('count'));
        
        const paymentMethodChart = new Chart(paymentCtx, {
            type: 'doughnut',
            data: {
                labels: paymentMethods.map(method => {
                    switch(method) {
                        case 'cash': return 'Tunai';
                        case 'bank_transfer': return 'Transfer Bank';
                        case 'credit_card': return 'Kartu Kredit';
                        case 'e_wallet': return 'E-Wallet';
                        default: return method;
                    }
                }),
                datasets: [{
                    data: paymentCounts,
                    backgroundColor: [
                        'rgb(34, 197, 94)',
                        'rgb(59, 130, 246)',
                        'rgb(168, 85, 247)',
                        'rgb(245, 158, 11)',
                        'rgb(239, 68, 68)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>
@endsection
