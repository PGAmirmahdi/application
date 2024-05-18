<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\DeliveryDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DeliveryDayController extends Controller
{
    public function index()
    {
        if (\request()->week){
            // days of week
            $items = [
                'saturday' => verta()->addWeek()->startWeek(),
                'sunday' => verta()->addWeek()->startWeek()->addDays(1),
                'monday' => verta()->addWeek()->startWeek()->addDays(2),
                'tuesday' => verta()->addWeek()->startWeek()->addDays(3),
                'wednesday' => verta()->addWeek()->startWeek()->addDays(4),
                'thursday' => verta()->addWeek()->startWeek()->addDays(5),
                'friday' => verta()->addWeek()->startWeek()->addDays(6),
            ];
        }else{
            // days of week
            $items = [
                'saturday' => verta()->startWeek(),
                'sunday' => verta()->startWeek()->addDays(1),
                'monday' => verta()->startWeek()->addDays(2),
                'tuesday' => verta()->startWeek()->addDays(3),
                'wednesday' => verta()->startWeek()->addDays(4),
                'thursday' => verta()->startWeek()->addDays(5),
                'friday' => verta()->startWeek()->addDays(6),
            ];
        }


        $days = $this->getDays($items);

        return view('panel.delivery-days.index', compact('days'));
    }

    public function toggleDay(Request $request)
    {
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

    private function getDays(array $week)
    {
        $days = [];

        foreach ($week as $day => $item) {
//            $url = "https://holidayapi.ir/jalali/{$item->year}/{$item->month}/{$item->day}";

//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            $res = json_decode(curl_exec($ch));
//            curl_close($ch);

            $days[$day] = [
                'date' => $item->format('Y/m/d'),
                'text' => $item->formatWord('l'),
//                'is_holiday' => $res->is_holiday,
                'is_holiday' => false,
                'is_selected' => (bool)DeliveryDay::where('date', $item->format('Y/m/d'))->first(),
            ];
        }

        return $days;
    }
}
