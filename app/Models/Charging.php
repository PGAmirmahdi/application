<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charging extends Model
{
    use HasFactory;
    protected $guarded = [];
    const type = [
        'harvest' => 'واریز',
        'settle' => 'برداشت'
    ];
    public function wallets()
    {
        return $this->belongsTo(Wallet::class);
    }
}
