@extends('master.layout')

@section('title', 'Dashboard')


@section('content')
    <div class="mb-6 mt-5">
        <h1 class="text-2xl font-bold text-gray-800">Welcome to Your Dashboard</h1>
        <p class="text-sm text-gray-500">Monitor key metrics and manage your application efficiently.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Card 1: Total Users -->
        <div
            class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 animate-fade-in flex items-center space-x-4">
            <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-600">Total Users</h3>
                <p class="text-2xl font-semibold text-gray-800">{{ \App\Models\User::count()}}</p>
            </div>
        </div>
        <!-- Card 2: Active Projects -->
        <div
            class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 animate-fade-in flex items-center space-x-4">
            <div class="p-3 bg-green-100 rounded-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-600">Total Roles</h3>
                <p class="text-2xl font-semibold text-gray-800">{{ \App\Models\Role::count()}}</p>
            </div>
        </div>
        <!-- Card 3: Total Revenue -->
        <div
            class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 animate-fade-in flex items-center space-x-4">
            <div class="p-3 bg-yellow-100 rounded-full">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-600">Total Revenue</h3>
                <p class="text-2xl font-semibold text-gray-800">
                    Rp {{ number_format(\App\Models\FinancialLog::where('financial_type', 'income')->sum('amount'), 0, ',', '.') }}
                </p>
            </div>
        </div>
        <!-- Card 4: Total Users with Lesson Packages -->
        <div
            class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 animate-fade-in flex items-center space-x-4">
            <div class="p-3 bg-purple-100 rounded-full">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-600">Users Beli Paket</h3>
                <p class="text-2xl font-semibold text-gray-800">{{ \App\Models\UserLessonPackage::distinct('user_id')->count('user_id') }}</p>
                {{-- <p class="text-xs text-blue-500">{{ \App\Models\LessonPackage::where('created_at', '>=', now()->subMonth())->count() }} new this month</p> --}}
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Quick Actions Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h2>
            <div class="space-y-3">
                <a href="{{ route('financial-create') }}" 
                   class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors duration-200">
                    <div class="p-2 bg-blue-600 rounded-lg mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <span class="text-gray-700 font-medium">Tambah Transaksi Keuangan</span>
                </a>
                <a href="{{ route('financial-index') }}" 
                   class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors duration-200">
                    <div class="p-2 bg-green-600 rounded-lg mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <span class="text-gray-700 font-medium">Lihat Laporan Keuangan</span>
                </a>
                <a href="{{ route('user-index') }}" 
                   class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors duration-200">
                    <div class="p-2 bg-purple-600 rounded-lg mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <span class="text-gray-700 font-medium">Kelola Users</span>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h2>
            <div class="space-y-3">
                @php
                    $recentLogs = \App\Models\FinancialLog::with('user')->latest()->take(5)->get();
                @endphp
                @forelse($recentLogs as $log)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="p-2 {{ $log->financial_type == 'income' ? 'bg-green-100' : 'bg-red-100' }} rounded-lg mr-3">
                            @if($log->financial_type == 'income')
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ \Str::limit($log->description, 30) }}</p>
                            <p class="text-xs text-gray-500">{{ $log->user->name ?? 'Unknown' }} • {{ $log->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <span class="text-sm font-semibold {{ $log->financial_type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $log->financial_type == 'income' ? '+' : '-' }}Rp {{ number_format($log->amount, 0, ',', '.') }}
                    </span>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">Belum ada aktivitas</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Transactions Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">Transaksi Terbaru</h2>
                <a href="{{ route('financial-index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Lihat Semua →
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $recentTransactions = \App\Models\FinancialLog::with('user')->latest()->take(10)->get();
                    @endphp
                    @forelse($recentTransactions as $transaction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $transaction->transaction_date ? $transaction->transaction_date->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ \Str::limit($transaction->description, 50) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $transaction->user->name ?? 'Unknown' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($transaction->financial_type == 'income')
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
                            @if($transaction->financial_type == 'income')
                                <span class="text-green-600">+ Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                            @else
                                <span class="text-red-600">- Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            Belum ada data transaksi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
