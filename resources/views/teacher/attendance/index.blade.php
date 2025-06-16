@extends('master.layout')

@section('title', 'Attendance Guru')

@section('content')
<div class="mt-4 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <!-- Attendance Hari Ini -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Attendance Hari Ini - {{ date('d/m/Y') }}</h3>
                </div>
                <div class="p-6">
                    @if(session('success'))
                        <div class="flash-message transition-opacity duration-500 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="flash-message transition-opacity duration-500 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif                    @if($todayAttendance)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-blue-600">Jam Buka</p>
                                        <p class="text-xl font-semibold text-blue-900">{{ Carbon\Carbon::parse($todayAttendance->open_time)->format('H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-red-600">Tutup</p>
                                        <p class="text-xl font-semibold text-red-900">{{ Carbon\Carbon::parse($todayAttendance->close_time)->format('H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-gray-100 text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Status</p>
                                        <div class="text-xl font-semibold">
                                            @if($todayAttendance->status === 'open')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Terbuka</span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Tertutup</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        @if($attendanceRecord)
                            <div class="bg-{{ $attendanceRecord->check_in_time ? 'green' : 'blue' }}-50 border border-{{ $attendanceRecord->check_in_time ? 'green' : 'blue' }}-200 rounded-xl p-4 mb-6">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-{{ $attendanceRecord->check_in_time ? 'green' : 'blue' }}-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <h5 class="text-sm font-semibold text-{{ $attendanceRecord->check_in_time ? 'green' : 'blue' }}-800 mb-2">Status Attendance Anda:</h5>
                                        @if($attendanceRecord->check_in_time)
                                            <p class="text-sm text-{{ $attendanceRecord->check_in_time ? 'green' : 'blue' }}-700 mb-1"><strong>Check In:</strong> {{ Carbon\Carbon::parse($attendanceRecord->check_in_time)->format('H:i:s') }}</p>
                                            <p class="text-sm text-{{ $attendanceRecord->check_in_time ? 'green' : 'blue' }}-700 mb-1"><strong>Status:</strong> 
                                                @switch($attendanceRecord->status)
                                                    @case('present')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Hadir</span>
                                                        @break
                                                    @case('absent')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Tidak Hadir/Alpa</span>
                                                        @break
                                                @endswitch
                                            </p>
                                            @if($attendanceRecord->notes)
                                                <p class="text-sm text-{{ $attendanceRecord->check_in_time ? 'green' : 'blue' }}-700"><strong>Catatan:</strong> {{ $attendanceRecord->notes }}</p>
                                            @endif
                                        @else
                                            <p class="text-sm text-{{ $attendanceRecord->check_in_time ? 'green' : 'blue' }}-700">Anda belum melakukan check in hari ini.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif                        @if($canCheckIn)
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                                <h5 class="text-lg font-semibold text-gray-900 mb-4">Check In Attendance</h5>
                                <form action="{{ route('teacher.attendance.check-in') }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                                        <textarea id="notes" 
                                                  name="notes" 
                                                  rows="3"
                                                  placeholder="Tambahkan catatan jika diperlukan"
                                                  class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 text-gray-800 resize-none"></textarea>
                                    </div>
                                    <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 px-4 rounded-xl hover:bg-blue-700 transition duration-200">
                                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Check In Sekarang
                                    </button>
                                </form>
                            </div>
                        @else                            @if($attendanceRecord && $attendanceRecord->check_in_time)
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-sm text-blue-700">Anda sudah melakukan check in hari ini.</p>
                                    </div>
                                </div>
                            @elseif($todayAttendance->status === 'closed')
                                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                        <p class="text-sm text-yellow-700">Attendance sudah ditutup.</p>
                                    </div>
                                </div>
                            @elseif(!$todayAttendance->isAvailableForTeachers())
                                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-red-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-sm text-red-700">Waktu attendance sudah berakhir (5 jam). Anda dianggap tidak hadir.</p>
                                    </div>
                                </div>
                            @endif
                        @endif                        @if($todayAttendance->description)
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mt-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <h6 class="text-sm font-semibold text-blue-800 mb-1">Deskripsi:</h6>
                                        <p class="text-sm text-blue-700 mb-0">{{ $todayAttendance->description }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                <p class="text-sm text-yellow-700">Belum ada attendance yang dibuka untuk hari ini.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            <!-- Clock -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Waktu Saat Ini</h3>
                </div>
                <div class="p-6 text-center">
                    <h2 id="current-time" class="text-2xl font-bold text-blue-600"></h2>
                    <p id="current-date" class="text-sm text-gray-500 mt-2"></p>
                </div>
            </div>            <!-- History Ringkas -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">History Terkini</h3>
                    <a href="{{ route('teacher.attendance.history') }}" class="text-sm bg-blue-100 text-blue-600 px-3 py-1 rounded-lg hover:bg-blue-200 transition-colors duration-200">
                        Lihat Semua
                    </a>
                </div>
                <div class="p-6">
                    @if($attendanceHistory->count() > 0)
                        <div class="space-y-3">
                            @foreach($attendanceHistory->take(5) as $record)
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-b-0">
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $record->attendance->attendance_date->format('d/m') }}</div>
                                        @if($record->check_in_time)
                                            <div class="text-sm text-gray-500">{{ Carbon\Carbon::parse($record->check_in_time)->format('H:i') }}</div>
                                        @endif
                                    </div>
                                    <div>
                                        @switch($record->status)
                                            @case('present')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Hadir</span>
                                                @break
                                            @case('absent')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Tidak Hadir/Alpa</span>
                                                @break
                                        @endswitch
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Belum ada history attendance</p>
                    @endif
                </div>
            </div>            <!-- Informasi -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi</h3>
                </div>
                <div class="p-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h6 class="text-sm font-semibold text-blue-800 mb-2">Aturan Attendance:</h6>
                                <ul class="text-sm text-blue-700 space-y-1 mb-0">
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                        Check in dalam 5 jam setelah attendance dibuka
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                        Tidak hadir jika tidak check in dalam 5 jam
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                        Semua check in dalam 5 jam dianggap hadir
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateTime() {
        const now = new Date();
        const timeOptions = { 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit',
            hour12: false 
        };
        const dateOptions = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        
        document.getElementById('current-time').textContent = now.toLocaleTimeString('id-ID', timeOptions);
        document.getElementById('current-date').textContent = now.toLocaleDateString('id-ID', dateOptions);
    }

    // Update time every second
    updateTime();
    setInterval(updateTime, 1000);
    
    // Flash Message auto disappear
    document.addEventListener('DOMContentLoaded', () => {
        const flashes = document.querySelectorAll('.flash-message');
        flashes.forEach(el => {
            setTimeout(() => {
                el.classList.add('opacity-0');
                setTimeout(() => el.remove(), 500);
            }, 3000);
        });
    });
</script>
@endsection
