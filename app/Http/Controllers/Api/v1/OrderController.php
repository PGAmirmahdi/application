<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderItemResource;
use App\Http\Resources\OrderResource;
use App\Models\Payment;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getOrders()
    {
        return OrderResource::collection(auth()->user()->orders()->with('user')->latest()->paginate(10));
    }

    public function getOrder(Request $request)
    {
        if (!isset($request->order_id) && !isset($request->authority)){
            return response()->json([
                'success' => false,
                'errors' => ['یکی از پارامتر های order_id و یا authority الزامی است']
            ]);
        }

        if ($request->authority){
            $payment = Payment::where('authority', $request->authority)->first();

            if (!$payment){
                 return response()->json([
                    'success' => false,
                    'errors' => ['سفارشی با این شناسه موجود نیست']
                ]);
            }

            $data = [
                'order' => OrderResource::make($payment->order),
                'order_items' => OrderItemResource::collection($payment->order->items),
            ];

            return response()->json($data);
        }

        if ($request->order_id){
            $order = auth()->user()->orders()->find($request->order_id);

            if (!$order){
                return response()->json([
                    'success' => false,
                    'errors' => ['سفارشی با این شناسه موجود نیست']
                ]);
            }

            return OrderResource::make($order);
        }
    }
}
