<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        // validation
        $validate = validator()->make($request->all(),[
            'user_id' => 'required',
            'address_id' => 'required',
            'items' => 'required|json',
        ]);

        if ($validate->fails()){
            return response()->json([
                'error' => true,
                'message' => $validate->errors()->getMessages()
            ]);
        }
        // end validation

        $user_id = $request->user_id;
        $items = json_decode($request->items, true);
        $address = Address::find($request->address_id);

        // create order
        $order = Order::create([
            'user_id' => $user_id,
            'province_id' => $address->province_id,
            'city' => $address->city,
            'address' => $address->full_address,
        ]);

        foreach ($items as $item) {
            $product = Product::find($item['product_id']);

            $order->items()->create([
                'product_id' => $item['product_id'],
                'count' => $item['count'],
                'price' => $product->price,
                'total_price' => ($product->price * $item['count']),
            ]);
        }
        // end create order

        $amount = $order->items()->sum('total_price');

        $data = [
            "merchant_id" => env('MERCHANT_ID'),
            "amount" => $amount,
            "callback_url" => 'https://artintoner.com',
            "description" => "خرید کالا",
        ];

        $jsonData = json_encode($data);

        $ch = curl_init('https://api.zarinpal.com/pg/v4/payment/request.json');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ]);

        $result = curl_exec($ch);
        $err = curl_error($ch);
        $result = json_decode($result, true);
        curl_close($ch);

        if ($err) {
            return response()->json([
                'error' => true,
                'message' => $err
            ]);
        } else {
            if (empty($result['errors'])) {
                if ($result['data']['code'] == 100) {
                    // create payment
                    Payment::create([
                        'order_id' => $order->id,
                        'authority' => $result['data']['authority'],
                        'amount' => $data['amount']
                    ]);
                    // end create payment

                    return response()->json([
                        'error' => false,
                        'url' => 'https://www.zarinpal.com/pg/StartPay/' . $result['data']['authority'],
                    ]);
                }
            } else {
                return response()->json([
                    'error' => true,
                    'error_code' => $result['errors']['code'],
                    'message' => $result['errors']['message']
                ]);
            }
        }
    }

    public function verify(Request $request)
    {
        // validation
        $validate = validator()->make($request->all(),[
            'authority' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'error' => true,
                'message' => $validate->errors()->getMessages()
            ]);
        }
        // end validation

        $Authority = $request->authority;
        $payment = Payment::where('authority', $Authority)->first();

        if (!$payment){
            return response()->json([
                'error' => true,
                'message' => 'تراکنشی یا این شناسه موجود نیست'
            ]);
        }

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
            'Content-Length: ' . strlen($jsonData)
        ]);

        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $result = json_decode($result, true);

        if ($err) {
            $payment->update(['status' => 'failed']);

            return response()->json([
                'error' => true,
                'message' => $err,
            ]);
        } else {
            if (isset($result['data']['code'])){
                if ($result['data']['code'] == 100) {
                    $payment->update([
                        'status' => 'success',
                        'ref_id' => $result['data']['ref_id'],
                        'verify_response' => json_encode($result),
                    ]);

                    $payment->order()->update(['status' => 'processing']);

                    return response()->json([
                        'error' => false,
                        'message' => 'Transation success. RefID:' . $result['data']['ref_id'],
                    ]);
                } elseif ($result['data']['code'] == 101) {
                    return response()->json([
                        'error' => false,
                        'message' => 'تراکنش با شناسه مورد نظر قبلا پرداخت موفق شده است',
                    ]);
                }
            } else {
                $payment->update(['status' => 'failed']);

                return response()->json([
                    'error' => true,
                    'error_code' => $result['errors']['code'],
                    'message' => $result['errors']['message'],
                ]);
            }
        }
    }
}
