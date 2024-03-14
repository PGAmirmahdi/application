<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getOrders()
    {
        return OrderResource::collection(auth()->user()->orders()->with('user')->latest()->paginate(10));
    }
}
