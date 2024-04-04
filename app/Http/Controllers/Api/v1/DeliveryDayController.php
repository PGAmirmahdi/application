<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeliveryDayResource;
use App\Models\DeliveryDay;
use Illuminate\Http\Request;

class DeliveryDayController extends Controller
{
    public function getDeliveryDays()
    {
        return DeliveryDayResource::collection(DeliveryDay::where('date', '>', verta()->format('Y/m/d'))->get());
    }
}
