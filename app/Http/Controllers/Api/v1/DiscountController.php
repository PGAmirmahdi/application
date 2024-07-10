<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
{
    public function getCoupon(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'code' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->messages(),
            ]);
        }

        $code = $request->code;
        $user_id = $request->user_id;

        // Check if the coupon exists
        $coupon = Coupon::where('code', $code)->first();
        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'کد تخفیف یافت نشد',
            ]);
        }

        // Check if the coupon is assigned to the specified user
        if ($coupon->user_id !== null && $coupon->user_id != $user_id) {
            return response()->json([
                'success' => false,
                'message' => 'این کد تخفیف متعلق به شما نیست',
            ]);
        }

        // Check if the coupon has been used in any successful payment
        $existingPayment = Payment::where('coupon_id', $coupon->id)
            ->where('status', 'success')
            ->first();
        if ($existingPayment && $coupon->type == 'once') {
            return response()->json([
                'success' => false,
                'message' => 'کد تخفیف قبلاً استفاده شده است',
            ]);
        }

        // Check if status is pending
        $existingPayment2 = Payment::where('coupon_id', $coupon->id)
            ->where('status', 'pending')
            ->first();
        if ($existingPayment2 && $coupon->type == 'once') {
            return response()->json([
                'success' => false,
                'message' => 'در حال حاضر امکان استفاده از کد تخفیف وجود ندارد',
            ]);
        }

        // Check if the coupon is single-use
        if ($coupon->type == 'once') {
            // Check if the coupon has been used at least once in a successful payment
            $usedCoupon = Payment::where('coupon_id', $coupon->id)
                ->where('status', 'success')
                ->exists();
            if ($usedCoupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'این کد تخفیف یکبار مصرف است و قبلاً استفاده شده است',
                ]);
            }
        }

        // Apply the coupon
        return response()->json([
            'success' => true,
            'message' => 'کد تخفیف با موفقیت اعمال شد',
            'data' => [
                'id' => $coupon->id,
                'limit' => $coupon->limit,
                'amount_pc' => $coupon->amount_pc,
            ],
        ]);
    }
}
