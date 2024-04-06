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

    public function isSelected(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'date' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        return response()->json([
            'data' => (bool)DeliveryDay::where('date', $request->date)->first()
        ]);
    }

    public function toggleDay(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'day' => 'required|json',
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $day = json_decode($request->day);

        if (DeliveryDay::where('date', $day->date)->first()){
            DeliveryDay::where('date', $day->date)->delete();
        }else{
            DeliveryDay::create([
                'date' => $day->date,
                'is_holiday' => $day->is_holiday,
            ]);
        }
    }
}
