<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
        'attendance_date' => 'date:Y-m-d',
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

    // Check apakah attendance masih bisa diakses guru
    public function isAvailableForTeachers()
    {
        if ($this->status !== 'open') {
            return false;
        }

        // Gabungkan tanggal attendance dengan close_time
        $closeDateTime = $this->getCloseDateTime();
        $now = Carbon::now();
        
        return $now <= $closeDateTime;
    }

    // Check apakah attendance sudah expired untuk admin
    public function isExpired()
    {
        $closeDateTime = $this->getCloseDateTime();
        $now = Carbon::now();
        
        return $now > $closeDateTime;
    }

    // Helper method untuk mendapatkan close datetime yang benar
    private function getCloseDateTime()
    {
        $attendanceDate = $this->attendance_date;
        $openTime = Carbon::createFromFormat('H:i:s', $this->open_time);
        $closeTime = Carbon::createFromFormat('H:i:s', $this->close_time);
        
        // Jika close_time lebih kecil dari open_time, berarti melewati tengah malam
        if ($closeTime->lessThan($openTime)) {
            // Close time di hari berikutnya
            $closeDateTime = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $attendanceDate->copy()->addDay()->format('Y-m-d') . ' ' . $this->close_time
            );
        } else {
            // Close time di hari yang sama
            $closeDateTime = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $attendanceDate->format('Y-m-d') . ' ' . $this->close_time
            );
        }
        
        return $closeDateTime;
    }

    // Method untuk debugging info
    public function getDebugInfo()
    {
        $closeDateTime = $this->getCloseDateTime();
        $now = Carbon::now();
        
        return [
            'id' => $this->id,
            'attendance_date' => $this->attendance_date->format('Y-m-d'),
            'open_time' => $this->open_time,
            'close_time' => $this->close_time,
            'status' => $this->status,
            'close_datetime' => $closeDateTime->format('Y-m-d H:i:s'),
            'current_time' => $now->format('Y-m-d H:i:s'),
            'is_available' => $this->isAvailableForTeachers(),
            'is_expired' => $this->isExpired(),
            'minutes_remaining' => $now->diffInMinutes($closeDateTime, false),
            'crosses_midnight' => Carbon::createFromFormat('H:i:s', $this->close_time)->lessThan(Carbon::createFromFormat('H:i:s', $this->open_time))
        ];
    }

    // Auto close attendance jika sudah melewati close_time (hanya jika status masih open)
    public function autoCloseIfExpired()
    {
        if ($this->isExpired() && $this->status === 'open') {
            $this->update(['status' => 'closed']);
            return true;
        }
        return false;
    }
}
