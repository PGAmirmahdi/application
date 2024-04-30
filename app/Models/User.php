<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'family',
        'role',
        'password',
        'phone',
        'national_code',
        'phone_code',
        'phone_expire',
        'fcm_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'phone_code',
        'phone_expire',
    ];

    const ROLE = [
        'admin' => 'ادمین',
        'user' => 'کاربر',
    ];

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class,'sender_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function returns()
    {
        return $this->hasMany(ReturnProduct::class);
    }

    public function fullName()
    {
        return $this->name . ' ' . $this->family;
    }

    public function isAdmin()
    {
        return $this->role == 'admin';
    }
}
