<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\DeviceInfo;
use Illuminate\Http\Request;

class DeviceStatusContrller extends Controller
{
    public function deviceinfo(Request $request)
    {
        $from = $request->From;

        $data = [
            'From' => $from,
        ];

        if ($from == 'Web') {
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

        DeviceInfo::create($data);

        return response()->json(['message' => 'Data stored successfully']);
    }
}
