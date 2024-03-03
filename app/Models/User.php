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

    public function fullName()
    {
        return $this->name . ' ' . $this->family;
    }

    public function isAdmin()
    {
        return auth()->user()->role == 'admin';
    }
}
