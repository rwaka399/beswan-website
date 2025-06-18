<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'user_id',
        'check_in_time',
        'status',
        'notes'
    ];

    protected $casts = [
        'check_in_time' => 'datetime:H:i:s',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function isLate()
    {
        return false;
    }

    public function setStatusBasedOnTime()
    {
        if (!$this->check_in_time || !$this->attendance) {
            $this->status = 'absent';
            return;
        }

        // Untuk saat ini, semua yang check-in dianggap present
        // Bisa ditambahkan logika untuk late berdasarkan open_time
        $this->status = 'present';
        
        // Optional: Implementasi logika terlambat
        // $openTime = Carbon::parse($this->attendance->attendance_date->format('Y-m-d') . ' ' . $this->attendance->open_time);
        // $checkInTime = Carbon::parse($this->attendance->attendance_date->format('Y-m-d') . ' ' . $this->check_in_time);
        // 
        // if ($checkInTime->diffInMinutes($openTime) > 15) { // 15 menit toleransi
        //     $this->status = 'late';
        // } else {
        //     $this->status = 'present';
        // }
    }
}
