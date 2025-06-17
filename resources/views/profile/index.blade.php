@extends('profile.layout')

@section('title', 'Profil Pengguna')

@section('content')
    <!-- Breadcrumb -->
    <div class="sticky top-12 inset-x-0 z-10 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center py-3">
            <ol class="flex items-center whitespace-nowrap">
                <li class="flex items-center text-base text-gray-800">
                    <a href="{{ route('profile-index')}}" class="hover:text-blue-600 font-medium">Profile</a>
                    <svg class="shrink-0 mx-3 overflow-visible size-3 text-gray-400" width="16" height="16"
                        viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </li>
                <li class="text-base font-semibold text-gray-800 truncate" aria-current="page">
                    Profile Pengguna
                </li>
            </ol>
        </div>
    </div>

    <!-- Profile Section -->
    <div class="max-w-4xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
        <div class="w-full bg-white rounded-3xl shadow-xl p-8 space-y-6">
            <!-- Header -->
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 21a8 8 0 0116 0H4z" />
                </svg>
                <h2 class="mt-3 text-2xl font-bold text-gray-800">Profil Pengguna</h2>
                <p class="text-sm text-gray-500">Informasi pribadi Anda</p>
            </div>

            <!-- Flash Message -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative transition-opacity duration-300"
                    role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative transition-opacity duration-300"
                    role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Profile Information -->
            <div class="space-y-6">
                <!-- Name -->
                <div class="flex items-center">
                    <span class="w-1/3 text-sm font-semibold text-gray-700">Nama Lengkap</span>
                    <span class="w-2/3 text-sm text-gray-900">{{ auth()->user()->name ?? '-' }}</span>
                </div>

                <!-- Email -->
                <div class="flex items-center">
                    <span class="w-1/3 text-sm font-semibold text-gray-700">Email</span>
                    <span class="w-2/3 text-sm text-gray-900">{{ auth()->user()->email ?? '-' }}</span>
                </div>

                <!-- Phone Number -->
                <div class="flex items-center">
                    <span class="w-1/3 text-sm font-semibold text-gray-700">Nomor Telepon</span>
                    <span class="w-2/3 text-sm text-gray-900">{{ auth()->user()->phone_number ?? '-' }}</span>
                </div>

                <!-- Province -->
                <div class="flex items-center">
                    <span class="w-1/3 text-sm font-semibold text-gray-700">Provinsi</span>
                    <span class="w-2/3 text-sm text-gray-900">{{ auth()->user()->province ?? '-' }}</span>
                </div>

                <!-- City -->
                <div class="flex items-center">
                    <span class="w-1/3 text-sm font-semibold text-gray-700">Kota/Kabupaten</span>
                    <span class="w-2/3 text-sm text-gray-900">{{ auth()->user()->city ?? '-' }}</span>
                </div>

                <!-- Kecamatan -->
                <div class="flex items-center">
                    <span class="w-1/3 text-sm font-semibold text-gray-700">Kecamatan</span>
                    <span class="w-2/3 text-sm text-gray-900">{{ auth()->user()->kecamatan ?? '-' }}</span>
                </div>

                <!-- Address -->
                <div class="flex items-start">
                    <span class="w-1/3 text-sm font-semibold text-gray-700">Alamat Lengkap</span>
                    <span class="w-2/3 text-sm text-gray-900">{{ auth()->user()->address ?? '-' }}</span>
                </div>
            </div>

            <!-- Premium Status Section -->
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6 border border-blue-100">
                <div class="flex items-center mb-4">
                    <svg class="w-6 h-6 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <h3 class="text-lg font-bold text-gray-800">Status Premium</h3>
                </div>

                @if(auth()->user()->isPremium())
                    @php
                        $remainingDays = auth()->user()->getRemainingPremiumDays();
                        $expiryDate = auth()->user()->getPremiumExpiryDate();
                    @endphp
                    
                    <div class="bg-white rounded-xl p-4 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-yellow-500 to-amber-500 text-white text-sm font-medium rounded-full">
                                    ⭐ Premium
                                </span>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-gray-800">{{ $remainingDays }} hari tersisa</div>
                                <div class="text-sm text-gray-500">Berakhir: {{ $expiryDate ? $expiryDate->format('d M Y') : '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('home') }}#paket" class="inline-block bg-blue-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                            Perpanjang Premium
                        </a>
                    </div>
                @else
                    <div class="bg-white rounded-xl p-4 border border-gray-200 text-center">
                        <div class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-600 text-sm font-medium rounded-full mb-3">
                            🔒 Belum Premium
                        </div>
                        <p class="text-gray-600 mb-4">Nikmati akses penuh dengan berlangganan premium</p>
                        <a href="{{ route('home') }}#paket" class="inline-block bg-blue-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                            Lihat Paket Premium
                        </a>
                    </div>
                @endif
            </div>

            <!-- Edit Button -->
            <div class="flex justify-end">
                <a href="{{ route('profile-edit') }}"
                    class="inline-block bg-blue-600 text-white font-semibold py-3 px-6 rounded-xl hover:bg-blue-700 transition duration-200">
                    Edit Profil
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Flash Message auto disappear
            const flashes = document.querySelectorAll('[role="alert"]');
            flashes.forEach(el => {
                setTimeout(() => {
                    el.classList.add('opacity-0');
                    setTimeout(() => el.remove(), 300);
                }, 3000);
            });
        });
    </script>
@endsection