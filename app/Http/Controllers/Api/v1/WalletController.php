<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    public function getWallet(Request $request)
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

        $item = Wallet::where('wallets.user_id', $user_id)
            ->join('users', 'wallets.user_id', '=', 'users.id')
            ->select([
                'wallets.id as wallet_id',
                'wallets.balance as balance',
                'wallets.user_id as user_id',
                'users.name as user_name',
                'users.family as user_family'
            ])
            ->first();

        if (!$item) {
            return response()->json([
                'success' => false,
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
