<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialLog extends Model
{
    protected $primaryKey = 'financial_log_id';

    protected $fillable = [
        'invoice_id',
        'user_id',
        'amount',
        'financial_type',
        'payment_method',
        'description',
        'transaction_date',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'datetime',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'invoice_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function lessonPackage()
    {
        return $this->hasOneThrough(
            LessonPackage::class,
            Invoice::class,
            'invoice_id',
            'lesson_package_id',
            'invoice_id',
            'lesson_package_id'
        );
    }
}