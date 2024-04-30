<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ReturnProduct;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function getReturns()
    {
        return auth()->user()->returns()->latest()->get();
    }

    public function createReturn(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'order_id' => 'required',
            'all' => 'required',
            'products' => 'nullable|json'
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        if (!Order::find($request->order_id)){
            return response()->json([
                'success' => false,
                'errors' => ['سفارشی با شناسه مورد نظر موجود نیست']
            ]);
        }

        ReturnProduct::create([
            'user_id' => auth()->id(),
            'order_id' => $request->order_id,
            'all' => (bool)$request->all,
            'products' => $request->products ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'مرجوع سفارش مورد نظر با موفقیت ثبت شد'
        ]);
    }
}
