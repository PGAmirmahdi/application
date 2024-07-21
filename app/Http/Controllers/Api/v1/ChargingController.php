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
        // Validate the request parameters
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->getMessages()
            ], 400);
        }

        // Retrieve user_id from the request
        $user_id = $request->user_id;

        // Query to get charging information along with user details
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
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'اطلاعات داده شد',
            'data' => $item
        ], 200);
    }


    public function Charge(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|integer', // Added integer validation
            'wallet_id' => 'required|integer', // Added integer validation
            'type'=>'string|required'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ], 400); // Added status code 400 for bad request
        }
        $user_id = $request->user_id;
        $wallet_id = $request->wallet_id;
        $type = $request->type;

    }
}
