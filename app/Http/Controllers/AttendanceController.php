<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\AttendanceRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    // Menampilkan daftar attendance untuk master
    public function index()
    {
        $attendances = Attendance::with(['creator', 'attendanceRecords.user'])
            ->orderBy('attendance_date', 'desc')
            ->paginate(10);

        // Auto close expired attendances
        foreach ($attendances as $attendance) {
            $attendance->autoCloseIfExpired();
        }

        return view('master.attendance.index', compact('attendances'));
    }

    // Form untuk membuat attendance baru
    public function create()
    {
        return view('master.attendance.create');
    }

    // Menyimpan attendance baru
    public function store(Request $request)
    {
        $request->validate([
            'attendance_date' => 'required|date|unique:attendances,attendance_date',
            'open_time' => 'required|date_format:H:i',
            'description' => 'nullable|string'
        ]);

        $openTime = Carbon::createFromFormat('H:i', $request->open_time);
        $closeTime = $openTime->copy()->addHours(5); // 5 jam setelah open_time

        $attendance = Attendance::create([
            'attendance_date' => $request->attendance_date,
            'open_time' => $openTime->format('H:i:s'),
            'close_time' => $closeTime->format('H:i:s'),
            'description' => $request->description,
            'status' => 'open', // Explicit set status
            'created_by' => Auth::user()->user_id,
            'updated_by' => Auth::user()->user_id,
        ]);

        // Buat record untuk semua guru aktif dengan status absent
        $teachers = User::whereHas('userRoles.role', function($query) {
            $query->where('role_name', 'Guru');
        })->get();

        foreach ($teachers as $teacher) {
            AttendanceRecord::create([
                'attendance_id' => $attendance->id,
                'user_id' => $teacher->user_id,
                'status' => 'absent',
                'check_in_time' => null,
                'notes' => null
            ]);
        }

        return redirect()->route('master.attendance.index')
            ->with('success', 'Attendance berhasil dibuat! ' . $teachers->count() . ' guru dapat melakukan absensi dalam 5 jam ke depan.');
    }

    // Menampilkan detail attendance
    public function show(Attendance $attendance)
    {
        $attendance->load(['attendanceRecords.user']);
        
        // Auto close jika expired, tapi jangan override manual close/open oleh admin
        $wasAutoClosed = $attendance->autoCloseIfExpired();
        
        $presentCount = $attendance->attendanceRecords->where('status', 'present')->count();
        $lateCount = $attendance->attendanceRecords->where('status', 'late')->count();
        $absentCount = $attendance->attendanceRecords->where('status', 'absent')->count();

        return view('master.attendance.show', compact('attendance', 'presentCount', 'lateCount', 'absentCount', 'wasAutoClosed'));
    }

    // Menutup attendance secara manual
    public function close(Attendance $attendance)
    {
        $attendance->update(['status' => 'closed']);

        return redirect()->back()
            ->with('success', 'Attendance berhasil ditutup.');
    }

    // Membuka kembali attendance
    public function reopen(Attendance $attendance)
    {
        // Admin bisa membuka kembali attendance kapan saja, 
        // tapi beri peringatan jika sudah melewati 5 jam
        if ($attendance->isExpired()) {
            $attendance->update(['status' => 'open']);
            return redirect()->back()
                ->with('warning', 'Attendance berhasil dibuka kembali. Perhatian: Waktu 5 jam sudah terlewati, tapi attendance tetap dibuka atas keputusan admin.');
        } else {
            $attendance->update(['status' => 'open']);
            return redirect()->back()
                ->with('success', 'Attendance berhasil dibuka kembali.');
        }
    }

    // Debug method untuk troubleshooting (hapus di production)
    public function debug(Attendance $attendance)
    {
        $debugInfo = $attendance->getDebugInfo();
        
        return response()->json([
            'attendance_info' => $debugInfo,
            'teachers_can_checkin' => $attendance->isAvailableForTeachers() && $attendance->status === 'open'
        ]);
    }

    // Method testing untuk skenario melewati tengah malam (hapus di production)
    public function testMidnightScenario()
    {
        $scenarios = [
            [
                'name' => 'Normal Day Attendance (9 AM - 2 PM)',
                'open_time' => '09:00:00',
                'close_time' => '14:00:00',
                'test_times' => ['08:30', '09:30', '13:30', '14:30']
            ],
            [
                'name' => 'Night Attendance (9 PM - 2 AM)',
                'open_time' => '21:00:00', 
                'close_time' => '02:00:00',
                'test_times' => ['20:30', '21:30', '23:30', '01:30', '02:30']
            ],
            [
                'name' => 'Late Night Attendance (11 PM - 4 AM)',
                'open_time' => '23:00:00',
                'close_time' => '04:00:00', 
                'test_times' => ['22:30', '23:30', '01:30', '03:30', '04:30']
            ]
        ];

        $results = [];
        $testDate = Carbon::today();
        
        foreach ($scenarios as $scenario) {
            $results[$scenario['name']] = [];
            
            // Simulasi attendance
            $attendance = new Attendance([
                'attendance_date' => $testDate,
                'open_time' => $scenario['open_time'],
                'close_time' => $scenario['close_time'],
                'status' => 'open'
            ]);
            
            foreach ($scenario['test_times'] as $testTime) {
                // Set waktu test
                Carbon::setTestNow($testDate->copy()->setTimeFromTimeString($testTime));
                
                $results[$scenario['name']][$testTime] = [
                    'current_time' => Carbon::now()->format('Y-m-d H:i:s'),
                    'is_available' => $attendance->isAvailableForTeachers(),
                    'is_expired' => $attendance->isExpired(),
                    'debug_info' => $attendance->getDebugInfo()
                ];
            }
        }
        
        // Reset waktu
        Carbon::setTestNow();
        
        return response()->json($results, 200, [], JSON_PRETTY_PRINT);
    }
}
