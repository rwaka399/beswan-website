<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_date',
        'open_time',
        'close_time',
        'status',
        'description',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'open_time' => 'datetime:H:i:s',
        'close_time' => 'datetime:H:i:s',
    ];

    // Relationship dengan User (creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    // Relationship dengan User (updater)
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_id');
    }

    // Relationship dengan AttendanceRecord
    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    // Check apakah attendance masih bisa diakses guru (sama dengan close_time)
    public function isAvailableForTeachers()
    {
        if ($this->status !== 'open') {
            return false;
        }

        $attendanceDateTime = Carbon::parse($this->attendance_date->format('Y-m-d') . ' ' . $this->close_time);
        $now = Carbon::now();
        
        return $now <= $attendanceDateTime;
    }

    // Check apakah attendance sudah expired untuk admin
    public function isExpired()
    {
        $attendanceDateTime = Carbon::parse($this->attendance_date->format('Y-m-d') . ' ' . $this->close_time);
        $now = Carbon::now();
        
        return $now > $attendanceDateTime;
    }

    // Auto close attendance jika sudah melewati close_time
    public function autoCloseIfExpired()
    {
        if ($this->isExpired() && $this->status === 'open') {
            $this->update(['status' => 'closed']);
        }
    }
}
