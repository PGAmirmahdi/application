<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChargingResource;
use App\Models\Address;
use App\Models\Charging;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ChargingController extends Controller
{
    public function getCharges()
    {
        return ChargingResource::collection(auth()->user()->charging()->with('users')->latest()->paginate(10));
    }
    public function getChargings(Request $request)
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
            'type' => 'required|string',
            'amount' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->getMessages(),
            ], 400); // Added status code 400 for bad request
        }
        $TomanToRial=$request->amount * 10;
        // Prepare data for ZarinPal API
        $data = [
            "merchant_id" => env('MERCHANT_ID'),
            "amount" => $TomanToRial,
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

                    // Create a charging record
                    $charging = Charging::create([
                        'user_id' => $request->user_id,
                        'amount' => $request->amount,
                        'type' => $request->type,
                        'description' => $request->description,
                        'tracking_code' => (string)random_int(1000000000, 9999999999),
                        'wallet_id' => $request->wallet_id,
                        'status' => 'pending',
                    ]);

                    // Create a payment record with charging_id
                    Payment::create([
                        'authority' => $result['data']['authority'],
                        'amount' => $data['amount'],
                        'tracking_code' => random_int(100000, 999999),
                        'wallet_id' => $request->wallet_id,
                        'charging_id' => $charging->id,
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
        $validate = validator()->make($request->all(),[
            'authority' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'error' => true,
                'message' => $validate->errors()->getMessages()
            ]);
        }

        // Retrieve authority from request
        $Authority = $request->authority;
        $payment = Payment::where('authority', $Authority)->first();

        if (!$payment){
            return response()->json([
                'error' => true,
                'message' => 'تراکنشی با این شناسه موجود نیست'
            ]);
        }

        // Prepare data for ZarinPal API
        $data = [
            "merchant_id" => env('MERCHANT_ID'),
            "authority" => $Authority,
            "amount" => $payment->amount
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
            Charging::where('id', $payment->charging_id)->update(['status' => 'failed']);

            return response()->json([
                'error' => true,
                'message' => $err,
            ]); // Added status code 500 for server error
        }

        if (isset($result['data']['code'])) {
            if ($result['data']['code'] == 100) {
                $payment->update([
                    'status' => 'success',
                    'ref_id' => $result['data']['ref_id'],
                    'verify_response' => json_encode($result),
                ]);
                Charging::where('id', $payment->charging_id)->update(['status' => 'successful']);

                // Update wallet balance
                $wallet = Wallet::where('id', $payment->wallet_id)->first();
                if ($wallet) {
                    $wallet->balance += $payment->amount;
                    $wallet->save();
                }

                return response()->json([
                    'error' => false,
                    'message' => 'Transaction success. RefID: ' . $result['data']['ref_id'],
                ]); // Added status code 200 for success
            } elseif ($result['data']['code'] == 101) {
                return response()->json([
                    'error' => false,
                    'message' => 'تراکنش با شناسه مورد نظر قبلا پرداخت موفق شده است',
                ]); // Added status code 200 for success
            }
        } else {
            $payment->update(['status' => 'failed']);
            Charging::where('id', $payment->charging_id)->update(['status' => 'failed']);

            return response()->json([
                'error' => true,
                'error_code' => $result['errors']['code'],
                'message' => $result['errors']['message'],
            ]); // Added status code 400 for bad request
        }
    }
    public function getVerifyUrl(Request $request)
    {
        // Validation
        $validate = validator()->make($request->all(),[
            'authority' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'error' => true,
                'message' => $validate->errors()->getMessages()
            ]);
        }

        // Retrieve authority from request
        $Authority = $request->authority;
        $payment = Payment::where('authority', $Authority)->first();

        if (!$payment){
            return response()->json([
                'error' => true,
                'message' => 'تراکنشی با این شناسه موجود نیست'
            ]);
        }

        // Log the payment status for debugging
        Log::info('Verifying session for authority: ' . $Authority, ['payment' => $payment]);

        // Check if the payment has already failed
        if ($payment->status === 'failed') {
            return response()->json([
                'error' => true,
                'error_code' => -51,
                'message' => 'Session is not valid, session is not active paid try.',
            ], 400);
        }

        // Determine URL based on presence of order_id or wallet_id
        if (isset($payment->order_id)) {
            $url = '/api/v1/payment-verify';
        } elseif (isset($payment->wallet_id)) {
            $url = '/api/v1/Charging-verify';
        } else {
            return response()->json([
                'error' => true,
                'message' => 'شناسه نامعتبر است',
            ]);
        }

        return response()->json([
            'error' => false,
            'url' => $url,
        ]);
    }




    public function getCharging(Request $request)
    {
        // Check for at least one of 'charging_id' or 'authority' in the request
        if (!isset($request->charging_id) && !isset($request->authority)) {
            return response()->json([
                'success' => false,
                'errors' => ['یکی از پارامتر های charging_id و یا authority الزامی است']
            ]);
        }

        // Handle case where 'authority' is provided
        if ($request->authority) {
            $payment = Payment::where('authority', $request->authority)->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'errors' => ['پرداختی با این شناسه موجود نیست']
                ]);
            }

            $charging = Charging::where('id', $payment->charging_id)->first();

            if (!$charging) {
                return response()->json([
                    'success' => false,
                    'errors' => ['سفارش مرتبط با این پرداخت یافت نشد']
                ]);
            }

            $data = [
                'charging' => ChargingResource::make($charging),
            ];

            return response()->json($data);
        }

        // Handle case where 'charging_id' is provided
        if ($request->charging_id) {
            $charging = Charging::find($request->charging_id);

            if (!$charging) {
                return response()->json([
                    'success' => false,
                    'errors' => ['سفارش با این شناسه موجود نیست']
                ]);
            }

            $data = [
                'charging' => ChargingResource::make($charging),
            ];

            return response()->json($data);
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
