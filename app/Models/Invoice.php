<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $primaryKey = 'invoice_id';

    protected $fillable = [
        'external_id',
        'invoice_url',
        'xendit_invoice_id',
        'user_id',
        'lesson_package_id',
        'amount',
        'payer_email',
        'status',
        'description',
        'payment_method',
        'expires_at',
        'midtrans_transaction_id',
        'midtrans_order_id',
        'midtrans_payment_code',
        'midtrans_transaction_status',
        'midtrans_response',
        'payment_gateway',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expires_at' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function lessonPackage()
    {
        return $this->belongsTo(LessonPackage::class, 'lesson_package_id', 'lesson_package_id');
    }

    public function financialLog()
    {
        return $this->hasOne(FinancialLog::class, 'invoice_id', 'invoice_id');
    }

}