<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\DeviceInfo;
use Illuminate\Http\Request;

class DeviceStatusContrller extends Controller
{
    public function deviceinfo(Request $request)
    {
        // اعتبارسنجی داده‌ها
        $validate = validator()->make($request->all(), [
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

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $from = $request->From;

        // تعیین داده‌های ذخیره‌شده براساس نوع درخواست
        $data = [
            'From' => $from,
        ];

        if ($from === 'Web') {
            $data['Browser_Name'] = $request->Browser_Name;
            $data['User_Agent'] = $request->User_Agent;
            $data['Platform'] = $request->Platform;
            $data['App_Version'] = $request->App_Version;
        } else {
            $data['Brand'] = $request->Brand;
            $data['Model'] = $request->Model;
            $data['Android_Version'] = $request->Android_Version;
            $data['Manufactor'] = $request->Manufactor;
        }

        // ذخیره داده‌ها در دیتابیس
        DeviceInfo::create($data);

        return response()->json([
            'success' => true,
            'message' => 'اطلاعات دستگاه با موفقیت ذخیره شد'
        ]);
    }

}
