<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    const STATUS = [
        'pending' => 'درحال بررسی',
        'closed' => 'بسته شده',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class,'sender_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
