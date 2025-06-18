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
        })->whereHas('userRoles', function($query) {
            // Pastikan relasi user_role masih aktif
            $query->whereNull('deleted_at');
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
        $attendance->autoCloseIfExpired();
        
        $presentCount = $attendance->attendanceRecords->where('status', 'present')->count();
        $absentCount = $attendance->attendanceRecords->where('status', 'absent')->count();

        return view('master.attendance.show', compact('attendance', 'presentCount', 'absentCount'));
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
        // Cek apakah masih dalam batas waktu 5 jam
        if (!$attendance->isExpired()) {
            $attendance->update(['status' => 'open']);
            return redirect()->back()
                ->with('success', 'Attendance berhasil dibuka kembali.');
        }

        return redirect()->back()
            ->with('error', 'Attendance sudah melewati batas waktu 5 jam dan tidak dapat dibuka kembali.');
    }
}
