<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STATUS = [
        'pending' => 'در انتظار پرداخت',
        'processing' => 'آماده سازی سفارش',
        'exit' => 'خروج از انبار',
        'sending' => 'درحال ارسال',
        'delivered' => 'تحویل به مشتری',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
