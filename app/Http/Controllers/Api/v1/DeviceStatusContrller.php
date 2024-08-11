<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeviceStatusContrller extends Controller
{
    public function deviceinfo(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'phone' => 'required',
            'code' => 'required',

        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }
    }
}
