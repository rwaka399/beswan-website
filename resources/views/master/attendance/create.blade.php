@extends('master.layout')

@section('title', 'Buat Attendance Baru')

@section('content')
    <!-- Breadcrumb -->
    <div class="sticky top-12 inset-x-0 z-10 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center py-3">
            <ol class="flex items-center whitespace-nowrap">
                <li class="flex items-center text-base text-gray-800">
                    <a href="{{ route('master.attendance.index') }}" class="hover:text-blue-600 font-medium">Attendance</a>
                    <svg class="shrink-0 mx-3 overflow-visible size-3 text-gray-400" width="16" height="16"
                        viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </li>
                <li class="text-base font-semibold text-gray-800 truncate" aria-current="page">
                    Buat Attendance Baru
                </li>
            </ol>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Create Attendance -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-xl p-8 space-y-6">
                    <!-- Header -->
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M15 13l-3 3m0 0l-3-3m3 3V8"/>
                        </svg>
                        <h2 class="mt-3 text-2xl font-bold text-gray-800">Buat Attendance Baru</h2>
                        <p class="text-sm text-gray-500">Atur waktu attendance untuk guru</p>
                    </div>

                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative transition-opacity duration-300" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Form -->
                    <form action="{{ route('master.attendance.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Tanggal Attendance -->
                        <div>
                            <label for="attendance_date" class="block text-sm font-semibold text-gray-700">
                                Tanggal Attendance <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   id="attendance_date" 
                                   name="attendance_date" 
                                   value="{{ old('attendance_date', date('Y-m-d')) }}"
                                   class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 text-gray-800 @error('attendance_date') border-red-500 @enderror" 
                                   required>
                            @error('attendance_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jam Buka Attendance -->
                        <div>
                            <label for="open_time" class="block text-sm font-semibold text-gray-700">
                                Jam Buka Attendance <span class="text-red-500">*</span>
                            </label>
                            <input type="time" 
                                   id="open_time" 
                                   name="open_time" 
                                   value="{{ old('open_time', '08:00') }}"
                                   class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 text-gray-800 @error('open_time') border-red-500 @enderror" 
                                   required>
                            @error('open_time')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">
                                Attendance akan otomatis tutup 5 jam setelah jam buka. 
                                Guru dapat melakukan absen kapan saja dalam periode 5 jam tersebut.
                            </p>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700">Deskripsi</label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Deskripsi attendance (opsional)"
                                      class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 text-gray-800 resize-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-4 pt-6">
                            <a href="{{ route('master.attendance.index') }}"
                                class="w-1/2 bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-xl hover:bg-gray-300 transition duration-200 text-center">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Kembali
                            </a>
                            <button type="submit"
                                class="w-1/2 bg-blue-600 text-white font-semibold py-3 px-4 rounded-xl hover:bg-blue-700 transition duration-200">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                </svg>
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Information Panel -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-xl p-6 space-y-6">
                    <!-- Info Card -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-center mb-3">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="text-sm font-semibold text-blue-800">Cara Kerja Attendance</h3>
                        </div>
                        <ul class="text-sm text-blue-700 space-y-2">
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                Admin membuka attendance dengan jam tertentu
                            </li>
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                Guru dapat melakukan absen kapan saja dalam <strong>5 jam</strong>
                            </li>
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                Attendance akan otomatis tertutup setelah <strong>5 jam</strong>
                            </li>
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                Guru yang tidak absen dalam 5 jam dianggap <strong>alpa</strong>
                            </li>
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                Semua guru yang absen dalam 5 jam dianggap <strong>hadir</strong>
                            </li>
                        </ul>
                    </div>

                    <!-- Warning Card -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                        <div class="flex items-center mb-3">
                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <h3 class="text-sm font-semibold text-yellow-800">Catatan Penting</h3>
                        </div>
                        <p class="text-sm text-yellow-700">
                            Pastikan tanggal attendance belum pernah dibuat sebelumnya. 
                            Satu hari hanya boleh ada satu attendance.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    // Set minimum date to today
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('attendance_date').min = new Date().toISOString().split('T')[0];
    });
</script>
@endsection
