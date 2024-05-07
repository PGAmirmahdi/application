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

    const STATUS_COLOR = [
        'pending' => 'ffb822',
        'success' => '0abb87',
        'failed' => 'e04b4b',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
