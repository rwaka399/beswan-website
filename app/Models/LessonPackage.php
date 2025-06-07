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
}
