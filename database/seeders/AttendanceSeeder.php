<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\AttendanceRecord;
use App\Models\User;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();
        
        // Buat attendance untuk hari ini
        $today = Carbon::today();
        $openTime = Carbon::createFromTime(8, 0); // 08:00
        $closeTime = $openTime->copy()->addHours(5); // 13:00

        $attendance = Attendance::create([
            'attendance_date' => $today,
            'open_time' => $openTime->format('H:i:s'),
            'close_time' => $closeTime->format('H:i:s'),
            'status' => 'open',
            'description' => 'Attendance rutin harian',
            'created_by' => $admin->user_id,
            'updated_by' => $admin->user_id,
        ]);

        // Buat attendance untuk kemarin (untuk testing history)
        $yesterday = Carbon::yesterday();
        $attendanceYesterday = Attendance::create([
            'attendance_date' => $yesterday,
            'open_time' => $openTime->format('H:i:s'),
            'close_time' => $closeTime->format('H:i:s'),
            'status' => 'closed',
            'description' => 'Attendance kemarin',
            'created_by' => $admin->user_id,
            'updated_by' => $admin->user_id,
        ]);

        // Ambil semua guru
        $teachers = User::whereHas('userRoles.role', function($query) {
            $query->where('role_name', 'Guru');
        })->get();

        // Buat records untuk attendance hari ini
        foreach ($teachers as $teacher) {
            AttendanceRecord::create([
                'attendance_id' => $attendance->id,
                'user_id' => $teacher->user_id,
                'status' => 'absent'
            ]);
        }

        // Buat records untuk attendance kemarin dengan beberapa status berbeda
        foreach ($teachers as $index => $teacher) {
            $status = 'absent';
            $checkInTime = null;

            // Simulasi beberapa guru hadir dan tidak hadir
            if ($index % 2 === 0) {
                // Guru hadir dalam 5 jam
                $status = 'present';
                $checkInTime = $openTime->copy()->addMinutes(rand(5, 300))->format('H:i:s'); // Random dalam 5 jam
            }
            // Sisanya tetap absent

            AttendanceRecord::create([
                'attendance_id' => $attendanceYesterday->id,
                'user_id' => $teacher->user_id,
                'check_in_time' => $checkInTime,
                'status' => $status,
                'notes' => $status === 'present' ? 'Hadir tepat waktu' : null
            ]);
        }
    }
}
