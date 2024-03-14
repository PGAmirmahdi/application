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

    const STATUS_COLOR = [
        'pending' => 'ffb822',
        'processing' => '0abb87',
        'exit' => '0abb87',
        'sending' => '0abb87',
        'delivered' => '0abb87',
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

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
