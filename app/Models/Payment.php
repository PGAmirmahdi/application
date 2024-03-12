<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STATUS = [
        'pending' => 'درانتظار پرداخت',
        'success' => 'موفق',
        'failed' => 'ناموفق',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
