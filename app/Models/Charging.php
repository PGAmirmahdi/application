<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charging extends Model
{
    use HasFactory;
    protected $guarded = [];
    const STATUS =[
        'pending' => 'درانتظار پرداخت',
        'success' => 'موفق',
        'failed' => 'ناموفق',
    ];
    const type = [
        'deposit' => 'واریز',
        'withdrawal' => 'برداشت'
    ];
    public function wallets()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
