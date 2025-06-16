<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\AttendanceRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TeacherAttendanceController extends Controller
{
    // Menampilkan halaman attendance untuk guru
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        // Cari attendance hari ini
        $todayAttendance = Attendance::where('attendance_date', $today)
            ->where('status', 'open')
            ->first();

        $attendanceRecord = null;
        $canCheckIn = false;

        if ($todayAttendance) {
            // Cari record attendance guru hari ini
            $attendanceRecord = AttendanceRecord::where('attendance_id', $todayAttendance->id)
                ->where('user_id', $user->user_id)
                ->first();

            // Cek apakah guru masih bisa check in
            $canCheckIn = $todayAttendance->isAvailableForTeachers() && 
                         (!$attendanceRecord || !$attendanceRecord->check_in_time);
        }

        // Ambil history attendance guru (7 hari terakhir)
        $attendanceHistory = AttendanceRecord::with('attendance')
            ->where('user_id', $user->user_id)
            ->whereHas('attendance', function($query) {
                $query->where('attendance_date', '>=', Carbon::now()->subDays(7));
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.attendance.index', compact(
            'todayAttendance',
            'attendanceRecord',
            'canCheckIn',
            'attendanceHistory'
        ));
    }

    // Melakukan check in attendance
    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        // Cari attendance hari ini
        $todayAttendance = Attendance::where('attendance_date', $today)
            ->where('status', 'open')
            ->first();

        if (!$todayAttendance) {
            return redirect()->back()
                ->with('error', 'Tidak ada attendance yang dibuka untuk hari ini.');
        }

        if (!$todayAttendance->isAvailableForTeachers()) {
            return redirect()->back()
                ->with('error', 'Waktu attendance sudah berakhir (5 jam). Anda dianggap tidak hadir.');
        }

        // Cari record attendance guru
        $attendanceRecord = AttendanceRecord::where('attendance_id', $todayAttendance->id)
            ->where('user_id', $user->user_id)
            ->first();

        if (!$attendanceRecord) {
            return redirect()->back()
                ->with('error', 'Record attendance tidak ditemukan.');
        }

        if ($attendanceRecord->check_in_time) {
            return redirect()->back()
                ->with('error', 'Anda sudah melakukan check in hari ini.');
        }

        // Update record dengan waktu check in
        $currentTime = Carbon::now()->format('H:i:s');
        $attendanceRecord->update([
            'check_in_time' => $currentTime,
            'notes' => $request->notes
        ]);

        // Set status berdasarkan waktu
        $attendanceRecord->setStatusBasedOnTime();
        $attendanceRecord->save();

        $statusMessage = '';
        switch ($attendanceRecord->status) {
            case 'present':
                $statusMessage = 'Check in berhasil! Anda hadir.';
                break;
            default:
                $statusMessage = 'Check in berhasil!';
                break;
        }

        return redirect()->back()->with('success', $statusMessage);
    }

    // Menampilkan history attendance guru
    public function history()
    {
        $user = Auth::user();
        
        $attendanceHistory = AttendanceRecord::with('attendance')
            ->where('user_id', $user->user_id)
            ->whereHas('attendance')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('teacher.attendance.history', compact('attendanceHistory'));
    }
}
