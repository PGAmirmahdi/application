<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuideVideos extends Model
{
    use HasFactory;
    protected $fillable=[
        'title',
        'text',
        'product_id',
        'user_id',
        'main_video'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
