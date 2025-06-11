@extends('master.layout')

@section('title', 'Dashboard Keuangan Saya')

@section('content')
    <div class="mt-4 max-w-full mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Keuangan Saya</h1>
            <div class="flex space-x-2">
                <a href="{{ route('financial-create') }}" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Tambah Transaksi
                </a>
                <a href="{{ route('financial-index') }}"
                    class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    Lihat Semua
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- My Total Income -->
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
                        <h3 class="text-sm font-medium text-green-800">Total Pemasukan Saya</h3>
                        <p class="text-2xl font-bold text-green-900">
                            Rp {{ number_format($myTotalIncome, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- My Total Expense -->
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
                        <h3 class="text-sm font-medium text-red-800">Total Pengeluaran Saya</h3>
                        <p class="text-2xl font-bold text-red-900">
                            Rp {{ number_format($myTotalExpense, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- My Total Transactions -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-blue-800">Total Transaksi Saya</h3>
                        <p class="text-2xl font-bold text-blue-900">
                            {{ $myTotalTransactions }}
                        </p>
                    </div>
                </div>
            </div>
        </div>        <!-- Quick Filters -->
        <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Filter Cepat</h2>
            <div class="flex flex-wrap gap-2 items-center">
                <a href="{{ route('financial-dashboard', ['period' => 'today']) }}" 
                    class="px-4 py-2 {{ request('period') == 'today' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-lg hover:bg-blue-500 hover:text-white">
                    Hari Ini
                </a>
                <a href="{{ route('financial-dashboard', ['period' => 'week']) }}" 
                    class="px-4 py-2 {{ request('period') == 'week' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-lg hover:bg-blue-500 hover:text-white">
                    Minggu Ini
                </a>
                  <!-- Month Selector -->
                <div class="month-selector">
                    <form method="GET" action="{{ route('financial-dashboard') }}" class="inline-block">
                        <select name="month" onchange="this.form.submit()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ request('month') ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-200 text-gray-700' }} transition-colors duration-200">
                            <option value="">Pilih Bulan</option>
                            @php
                                $months = [
                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                ];
                                $currentYear = date('Y');
                            @endphp
                            @foreach($months as $monthNum => $monthName)
                                <option value="{{ $monthNum }}" 
                                    {{ request('month') == $monthNum ? 'selected' : '' }}>
                                    {{ $monthName }} {{ $currentYear }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                
                <a href="{{ route('financial-dashboard', ['period' => 'year']) }}" 
                    class="px-4 py-2 {{ request('period') == 'year' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-lg hover:bg-blue-500 hover:text-white">
                    Tahun Ini
                </a>
                <a href="{{ route('financial-dashboard') }}" 
                    class="px-4 py-2 {{ !request('period') && !request('month') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-lg hover:bg-blue-500 hover:text-white">
                    Semua
                </a>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Transaksi Terbaru Saya</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentTransactions as $log)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->transaction_date ? $log->transaction_date->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ Str::limit($log->description, 50) ?? '-' }}
                            </td>                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($log->financial_type == 'income')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Pemasukan
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Pengeluaran
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($log->financial_type == 'income')
                                    <span class="text-green-600">+ Rp {{ number_format($log->amount, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-red-600">- Rp {{ number_format($log->amount, 0, ',', '.') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ ucfirst(str_replace('_', ' ', $log->payment_method ?? '-')) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('financial-show', $log->financial_log_id) }}"
                                    class="text-blue-600 hover:text-blue-900 mr-2">Lihat</a>
                                <a href="{{ route('financial-edit', $log->financial_log_id) }}"
                                    class="text-yellow-600 hover:text-yellow-900">Edit</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                Belum ada transaksi keuangan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($recentTransactions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $recentTransactions->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <!-- Monthly Breakdown Chart -->
        @if(count($myMonthlyData) > 0)
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Tren Keuangan Saya (6 Bulan Terakhir)</h2>
            <div class="h-64">
                <canvas id="myMonthlyChart"></canvas>
            </div>
        </div>
        @endif
    </div>

    @if(count($myMonthlyData) > 0)
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // My Monthly Chart
        const myMonthlyCtx = document.getElementById('myMonthlyChart').getContext('2d');
        const myMonthlyChart = new Chart(myMonthlyCtx, {
            type: 'bar',            data: {
                labels: @json($myMonthlyData->pluck('month_name')),
                datasets: [{
                    label: 'Pemasukan',
                    data: @json($myMonthlyData->pluck('income')),
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 1
                }, {
                    label: 'Pengeluaran',
                    data: @json($myMonthlyData->pluck('expense')),
                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                    borderColor: 'rgb(239, 68, 68)',
                    borderWidth: 1
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
        });    </script>
    
    <style>
        /* Custom styling for month selector to match filter buttons */
        .month-selector select {
            background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>');
            background-repeat: no-repeat;
            background-position: right 8px center;
            background-size: 16px;
            padding-right: 32px;
            appearance: none;
        }
        
        .month-selector select:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }
        
        .month-selector select option {
            background-color: white;
            color: #374151;
        }
    </style>
    
    <script>
        // Clear month selection when period buttons are clicked
        document.addEventListener('DOMContentLoaded', function() {
            const periodLinks = document.querySelectorAll('a[href*="period="]');
            const monthSelect = document.querySelector('select[name="month"]');
            
            periodLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (monthSelect) {
                        monthSelect.value = '';
                    }
                });
            });
            
            // Clear period parameter when month is selected
            if (monthSelect) {
                monthSelect.addEventListener('change', function() {
                    if (this.value) {
                        // Remove period from URL if month is selected
                        const url = new URL(window.location);
                        url.searchParams.delete('period');
                        window.history.replaceState({}, '', url);
                    }
                });
            }
        });
    </script>
    @endif
@endsection
