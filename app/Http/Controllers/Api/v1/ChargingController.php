<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Charging;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChargingController extends Controller
{
    public function getCharging(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|integer', // Added integer validation
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ], 400); // Added status code 400 for bad request
        }

        $user_id = $request->user_id;
        $item = Charging::where('chargings.user_id', $user_id)
            ->join('users', 'chargings.user_id', '=', 'users.id')
            ->select([
                'chargings.id as wallet_id',
                'chargings.amount as amount',
                'chargings.type as type',
                'chargings.description as description',
                'chargings.tracking_code as tracking_code',
                'chargings.created_at as created_at',
                'users.name as user_name',
                'users.family as user_family'
            ])
            ->first();
        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'تراکنش یافت نشد',
                'data' => []
            ], 404); // Added status code 404 for not found
        }

        return response()->json([
            'success' => true,
            'message' => 'اطلاعات داده شد',
            'data' => $item // Removed array wrapping
        ], 200); // Added status code 200 for success
    }
}
