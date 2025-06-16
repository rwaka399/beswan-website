<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonPackage extends Model
{
    use HasFactory;

    protected $primaryKey = 'lesson_package_id';
    protected $fillable = [
        'lesson_package_name',
        'lesson_package_description',
        'lesson_duration',
        'duration_unit',
        'lesson_package_price',
        'created_by',
        'updated_by',
    ];
    
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_id');
    }

    // Relasi dengan Invoice
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'lesson_package_id', 'lesson_package_id');
    }

    // Method untuk menampilkan durasi yang user-friendly
    public function getFormattedDurationAttribute()
    {
        return $this->lesson_duration . ' ' . $this->duration_unit;
    }
    
    // Method untuk menghitung durasi dalam hari (untuk countdown)
    public function getDurationInDaysAttribute()
    {
        switch ($this->duration_unit) {
            case 'hari':
                return $this->lesson_duration;
            case 'minggu':
                return $this->lesson_duration * 7;
            case 'bulan':
                return $this->lesson_duration * 30; // Asumsi 30 hari per bulan
            default:
                return $this->lesson_duration;
        }
    }
    
    // Method untuk menghitung tanggal berakhir berdasarkan tanggal mulai
    public function getEndDate($startDate)
    {
        $start = \Carbon\Carbon::parse($startDate);
        
        switch ($this->duration_unit) {
            case 'hari':
                return $start->addDays($this->lesson_duration);
            case 'minggu':
                return $start->addWeeks($this->lesson_duration);
            case 'bulan':
                return $start->addMonths($this->lesson_duration);
            default:
                return $start->addDays($this->lesson_duration);
        }
    }
}
