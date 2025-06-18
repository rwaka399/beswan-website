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
        
        // Validasi apakah user adalah guru
        if (!$user->hasRole('Guru')) {
            abort(403, 'Akses ditolak. Hanya guru yang dapat mengakses halaman ini.');
        }

        $today = Carbon::today();
        
        // Cari attendance hari ini
        $todayAttendance = Attendance::where('attendance_date', $today)
            ->where('status', 'open')
            ->first();

        $attendanceRecord = null;
        $canCheckIn = false;

        if ($todayAttendance) {
            // Auto close jika sudah expired
            $todayAttendance->autoCloseIfExpired();
            
            // Cari record attendance guru hari ini
            $attendanceRecord = AttendanceRecord::where('attendance_id', $todayAttendance->id)
                ->where('user_id', $user->user_id)
                ->first();

            // Cek apakah guru masih bisa check in
            $canCheckIn = $todayAttendance->isAvailableForTeachers() && 
                         $todayAttendance->status === 'open' &&
                         (!$attendanceRecord || !$attendanceRecord->check_in_time);
        }

        // Ambil history attendance guru (7 hari terakhir)
        $attendanceHistory = AttendanceRecord::with('attendance')
            ->where('user_id', $user->user_id)
            ->whereHas('attendance', function($query) {
                $query->where('attendance_date', '>=', Carbon::now()->subDays(7));
            })
            ->orderBy('created_at', 'desc')
            ->take(5) // Limit to 5 most recent
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
        
        // Validasi apakah user adalah guru
        if (!$user->hasRole('Guru')) {
            return redirect()->back()
                ->with('error', 'Akses ditolak. Hanya guru yang dapat melakukan check-in.');
        }

        $today = Carbon::today();
        
        // Cari attendance hari ini
        $todayAttendance = Attendance::where('attendance_date', $today)
            ->where('status', 'open')
            ->first();

        if (!$todayAttendance) {
            return redirect()->back()
                ->with('error', 'Tidak ada attendance yang dibuka untuk hari ini.');
        }

        // Auto close jika sudah expired
        $todayAttendance->autoCloseIfExpired();
        
        if (!$todayAttendance->isAvailableForTeachers() || $todayAttendance->status !== 'open') {
            return redirect()->back()
                ->with('error', 'Waktu attendance sudah berakhir atau attendance sudah ditutup.');
        }

        // Cari record attendance guru
        $attendanceRecord = AttendanceRecord::where('attendance_id', $todayAttendance->id)
            ->where('user_id', $user->user_id)
            ->first();

        if (!$attendanceRecord) {
            return redirect()->back()
                ->with('error', 'Record attendance tidak ditemukan. Silakan hubungi administrator.');
        }

        if ($attendanceRecord->check_in_time) {
            return redirect()->back()
                ->with('error', 'Anda sudah melakukan check in hari ini pada ' . Carbon::parse($attendanceRecord->check_in_time)->format('H:i:s') . '.');
        }

        // Update record dengan waktu check in
        $currentTime = Carbon::now();
        $attendanceRecord->update([
            'check_in_time' => $currentTime->format('H:i:s'),
            'notes' => $request->notes ?? null
        ]);

        // Set status berdasarkan waktu
        $attendanceRecord->setStatusBasedOnTime();
        $attendanceRecord->save();

        $statusMessage = 'Check in berhasil pada ' . $currentTime->format('H:i:s') . '! ';
        switch ($attendanceRecord->status) {
            case 'present':
                $statusMessage .= 'Status: Hadir.';
                break;
            case 'late':
                $statusMessage .= 'Status: Terlambat.';
                break;
            default:
                $statusMessage .= 'Status: ' . ucfirst($attendanceRecord->status) . '.';
                break;
        }

        return redirect()->back()->with('success', $statusMessage);
    }

    // Menampilkan history attendance guru
    public function history()
    {
        $user = Auth::user();
        
        // Validasi akses - temporary skip, akan dihandle di route middleware
        // if (!$user->canAccessMaster()) {
        //     abort(403, 'Akses ditolak.');
        // }
        
        $attendanceHistory = AttendanceRecord::with(['attendance' => function($query) {
                $query->orderBy('attendance_date', 'desc');
            }])
            ->where('user_id', $user->user_id)
            ->whereHas('attendance')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('teacher.attendance.history', compact('attendanceHistory'));
    }
}
