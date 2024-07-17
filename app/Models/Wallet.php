<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'balance',
        'user_id'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function chargins()
    {
        return $this->hasmany(Charging::class);
    }
}
