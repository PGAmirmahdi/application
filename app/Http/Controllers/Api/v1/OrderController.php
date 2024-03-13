<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getOrders()
    {
        return auth()->user()->orders()->latest()->paginate(10);
    }
}
