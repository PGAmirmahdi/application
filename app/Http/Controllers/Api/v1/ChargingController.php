<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Charging;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\Wallet;
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
        // Validate the request parameters
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'wallet_id' => 'required',
            'address_id' => 'required',
            'type' => 'required|string',
            'amount' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->getMessages(),
            ], 400); // Added status code 400 for bad request
        }

        // Prepare data for ZarinPal API
        $data = [
            "merchant_id" => env('MERCHANT_ID'),
            "amount" => $request->amount,
            "callback_url" => 'https://app.mpsystem.ir/BackToApp',
            "description" => "شارژ کیف پول",
        ];

        $jsonData = json_encode($data);
        $ch = curl_init('https://api.zarinpal.com/pg/v4/payment/request.json');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v4');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData),
        ]);

        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $result = json_decode($result, true);

        if ($err) {
            return response()->json([
                'error' => true,
                'message' => $err,
            ], 500); // Added status code 500 for server error
        } else {
            if (empty($result['errors'])) {
                if ($result['data']['code'] == 100) {
                    // Create a payment record
                    Payment::create([
                        'authority' => $result['data']['authority'],
                        'amount' => $data['amount'],
                        'tracking_code' => random_int(100000, 999999),
                        'wallet_id' => $request->wallet_id,
                    ]);

                    // Create a charging record
                    Charging::create([
                        'user_id' => $request->user_id,
                        'amount' => $request->amount,
                        'type' => $request->type,
                        'description' => $request->description,
                        'tracking_code' => (string) random_int(1000000000, 9999999999),
                        'wallet_id' => $request->wallet_id,
                        'status' => 'pending',
                    ]);

                    return response()->json([
                        'error' => false,
                        'url' => 'https://www.zarinpal.com/pg/StartPay/' . $result['data']['authority'],
                    ], 200); // Added status code 200 for success
                }
            } else {
                return response()->json([
                    'error' => true,
                    'error_code' => $result['errors']['code'],
                    'message' => $result['errors']['message'],
                ], 400); // Added status code 400 for bad request
            }
        }
    }


    public function verify(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'authority' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()->getMessages(),
            ], 400); // Added status code 400 for bad request
        }

        // Retrieve authority from request
        $authority = $request->authority;
        $payment = Payment::where('authority', $authority)->first();

        if (!$payment) {
            return response()->json([
                'error' => true,
                'message' => 'تراکنشی با این شناسه موجود نیست',
            ], 404); // Added status code 404 for not found
        }

        // Prepare data for ZarinPal API
        $data = [
            "merchant_id" => env('MERCHANT_ID'),
            "authority" => $authority,
            "amount" => $payment->amount,
        ];

        $jsonData = json_encode($data);
        $ch = curl_init('https://api.zarinpal.com/pg/v4/payment/verify.json');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v4');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData),
        ]);

        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $result = json_decode($result, true);

        if ($err) {
            $payment->update(['status' => 'failed']);
            $payment->charging()->update(['status' => 'canceled']);

            return response()->json([
                'error' => true,
                'message' => $err,
            ], 500); // Added status code 500 for server error
        }

        if (isset($result['data']['code'])) {
            if ($result['data']['code'] == 100) {
                $payment->update([
                    'status' => 'success',
                    'ref_id' => $result['data']['ref_id'],
                    'verify_response' => json_encode($result),
                ]);

                $payment->charging()->update(['status' => 'successful']);

                // Update wallet balance
                $wallet = Wallet::where('wallet_id', $payment->wallet_id)->first();
                $wallet->balance += $payment->amount;
                $wallet->save();

                return response()->json([
                    'error' => false,
                    'message' => 'Transaction success. RefID: ' . $result['data']['ref_id'],
                ], 200); // Added status code 200 for success
            } elseif ($result['data']['code'] == 101) {
                return response()->json([
                    'error' => false,
                    'message' => 'تراکنش با شناسه مورد نظر قبلا پرداخت موفق شده است',
                ], 200); // Added status code 200 for success
            }
        } else {
            $payment->update(['status' => 'failed']);
            $payment->charging()->update(['status' => 'canceled']);

            return response()->json([
                'error' => true,
                'error_code' => $result['errors']['code'],
                'message' => $result['errors']['message'],
            ], 400); // Added status code 400 for bad request
        }
    }

//    public function verify(Request $request)
//    {
//        $validate = Validator::make($request->all(), [
//            'user_id' => 'required', // Added integer validation
//            'wallet_id' => 'required', // Added integer validation
//            'type'=>'string|required',
//            'amount'=>'required',
//        ]);
//        if ($validate->fails()) {
//            return response()->json([
//                'success' => false,
//                'errors' => $validate->errors()->getMessages()
//            ], 400); // Added status code 400 for bad request
//        }
//        // Fetch the user and their wallet
//        $user = User::findOrFail($request->user_id);
//        $wallet = $user->wallets;
//
//        // Perform the transaction based on type (deposit or withdrawal)
//        $amount = $request->amount;
//        $type = $request->type;
//
//        if ($type === 'deposit') {
//            $wallet->balance += $amount;
//        } elseif ($type === 'withdrawal') {
//            if ($wallet->balance < $amount) {
//                return response()->json(['error' => 'موجودی کافی نیست.'], 422);
//            }
//            $wallet->balance -= $amount;
//        }
//
//        // Save the wallet after the transaction
//        $wallet->save();
//
//        // Create a charging record
//        $item = Charging::create([
//            'user_id' => $user->id,
//            'amount' => $amount,
//            'type' => $type,
//            'description' => $request->description,
//            'tracking_code' => (string) random_int(1000000000, 9999999999),
//            'wallet_id'=>$wallet->id
//        ]);
//
//        // Redirect to index page on success
//        return response()->json([
//            'success' => true,
//            'message' => 'تراکنش موفق',
//            'data' => $item
//        ], 200);
//    }

}
