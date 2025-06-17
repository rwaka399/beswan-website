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
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
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
}
