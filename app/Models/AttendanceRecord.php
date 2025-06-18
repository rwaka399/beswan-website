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
        // Remove the datetime cast for check_in_time since it's stored as TIME
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

        // Parse waktu check-in dan open_time
        $openDateTime = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $this->attendance->attendance_date->format('Y-m-d') . ' ' . $this->attendance->open_time
        );
        
        $checkInDateTime = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $this->attendance->attendance_date->format('Y-m-d') . ' ' . $this->check_in_time
        );
        
        // Toleransi terlambat: 15 menit setelah open_time
        $lateThreshold = $openDateTime->copy()->addMinutes(15);
        
        if ($checkInDateTime->greaterThan($lateThreshold)) {
            $this->status = 'late';
        } else {
            $this->status = 'present';
        }
    }
}
