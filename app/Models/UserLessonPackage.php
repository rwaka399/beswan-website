<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLessonPackage extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_lesson_package_id';

    protected $fillable = [
        'user_id',
        'lesson_package_id',
        'invoice_id',
        'purchased_at',
        'scheduled_start_date',
        'start_date',
        'end_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'purchased_at' => 'datetime',
        'scheduled_start_date' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    // Relasi ke User
    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relasi ke LessonPackage
    public function lessonPackage() :BelongsTo
    {
        return $this->belongsTo(LessonPackage::class, 'lesson_package_id', 'lesson_package_id');
    }

    // Relasi ke Invoice (jika ada model Invoice)
    public function invoice() :BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'invoice_id');
    }

    /**
     * Memeriksa apakah paket sudah waktunya diaktifkan
     * 
     * @return bool
     */
    public function shouldBeActivated()
    {
        return $this->status === 'scheduled' && 
               now()->greaterThanOrEqualTo($this->scheduled_start_date);
    }

    /**
     * Mengaktifkan paket yang masuk jadwal
     * 
     * @return bool
     */
    public function activateIfScheduled()
    {
        if ($this->shouldBeActivated()) {
            $this->status = 'active';
            $this->start_date = now();
            return $this->save();
        }
        
        return false;
    }

    /**
     * Scope untuk mendapatkan paket yang sudah dijadwalkan dan saatnya diaktifkan
     */
    public function scopeScheduledToStart($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('scheduled_start_date', '<=', now());
    }
}
