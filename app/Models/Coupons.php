<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function Order()
    {
        return $this->belongsTo(Order::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
