@extends('layouts.app')

@section('title', 'Pembayaran Gagal')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center">
        <!-- Error Icon -->
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-6">
            <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>

        <!-- Error Message -->
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Pembayaran Gagal</h1>
        <p class="text-gray-600 mb-8">
            Maaf, pembayaran Anda tidak dapat diproses. 
            Silakan coba lagi dengan metode pembayaran yang berbeda.
        </p>

        <!-- Possible Reasons -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 text-left">
            <h4 class="font-medium text-red-900 mb-2">Kemungkinan Penyebab:</h4>
            <ul class="text-sm text-red-800 space-y-1">
                <li>• Saldo tidak mencukupi</li>
                <li>• Koneksi internet terputus</li>
                <li>• Pembayaran dibatalkan</li>
                <li>• Masalah teknis pada payment gateway</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3">
            <a href="{{ url()->previous() }}" 
               class="w-full bg-red-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-red-700 transition duration-200 inline-block">
                Coba Lagi
            </a>
            <a href="{{ route('home') }}" 
               class="w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-lg font-medium hover:bg-gray-200 transition duration-200 inline-block">
                Kembali ke Beranda
            </a>
        </div>

        <!-- Help Section -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <p class="text-xs text-gray-500 mb-2">Butuh bantuan?</p>
            <p class="text-sm text-gray-600">
                Hubungi tim support kami jika masalah terus berlanjut.
            </p>
            <div class="mt-2">
                <a href="mailto:support@beswan.com" class="text-sm text-blue-600 hover:text-blue-800">
                    support@beswan.com
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
