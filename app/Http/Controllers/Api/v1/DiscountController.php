<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Coupons;
use App\Models\Order;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function getCoupon(Request $request)
    {
        $validate = validator()->make($request->all(), [
            'code' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages(),
            ]);
        }

        $code = $request->code;

        $order = Order::whereHas('coupon', function ($query) use ($code) {
            $query->where('code', '=', $code);
        })->first();

        if ($order) {
            return response()->json([
                'success' => false,
                'message' => 'کد قبلا استفاده شده',
            ]);
        }

        $coupon = Coupons::where('code', '=', $code)
            ->select(['limit', 'amount_pc'])
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'کد یافت نشد',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'اطلاعات داده شد',
            'data' => $coupon,
        ]);
    }
}
