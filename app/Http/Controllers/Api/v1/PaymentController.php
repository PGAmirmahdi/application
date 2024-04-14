<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        $amount = ($order->items()->sum('total_price') * 10);

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
                'message' => 'تراکنشی با این شناسه موجود نیست'
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
            $payment->order()->update(['status' => 'canceled']);

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

                    // send to mpsystem
                    $this->sendInvoice($payment);
                    // end send to mpsyste

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
                $payment->order()->update(['status' => 'canceled']);

                return response()->json([
                    'error' => true,
                    'error_code' => $result['errors']['code'],
                    'message' => $result['errors']['message'],
                ]);
            }
        }
    }

    private function sendInvoice($payment)
    {
        $items = [];

        foreach ($payment->order->items as $item){
            $items[] = [
                'acc_code' => $item->product->code,
                'total' => $item->total_price,
                'quantity' => $item->count,
            ];
        }

        $jsonData = [
            'first_name' => $payment->order->user->name,
            'last_name' => $payment->order->user->family,
            'national_number' => $payment->order->user->national_code,
            'province' => $payment->order->province->name,
            'city' => $payment->order->city,
            'address_1' => $payment->order->address,
            'postal_code' => '000',
            'phone' => $payment->order->user->phone,
            'national_code' => $payment->order->user->national_code,
            'items' => $items,
            'created_in' => 'app'
        ];

        $jsonData = json_encode($jsonData);
        $ch = curl_init('https://193.105.234.70/api/invoice-create');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ]);

        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
    }
}
