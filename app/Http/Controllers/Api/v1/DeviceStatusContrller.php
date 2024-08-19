<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\DeviceInfo;
use Illuminate\Http\Request;

class DeviceStatusContrller extends Controller
{
    public function deviceinfo(Request $request)
    {
        $validatedData = $request->validate([
            'From' => 'required|string',
            'Browser_Name' => 'nullable|string',
            'User_Agent' => 'nullable|string',
            'Platform' => 'nullable|string',
            'App_Version' => 'nullable|string',
            'Brand' => 'nullable|string',
            'Model' => 'nullable|string',
            'Android_Version' => 'nullable|string',
            'Manufactor' => 'nullable|string',
        ]);

        // ذخیره داده‌ها در دیتابیس
        DeviceInfo::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'اطلاعات دستگاه با موفقیت ذخیره شد',
            'data'=> $validatedData
        ]);
    }

}
