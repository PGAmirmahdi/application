<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReturnResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\ReturnProduct;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function getReturns()
    {
        return ReturnResource::collection(auth()->user()->returns()->latest()->get());
    }

    public function getOrderItems(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'order_id' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $order = Order::find($request->order_id);
        if (!$order){
            return response()->json([
                'success' => false,
                'errors' => ['سفارشی با شناسه مورد نظر موجود نیست']
            ]);
        }
        $products = [];

        foreach ($order->items as $item){
            $product = Product::find($item->product_id);

            $products[] = [
                'title' => $product->title,
                'main_image' => $product->main_image,
                'count' => $item->count,
            ];
        }

        return $products;
    }

    public function createReturn(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'order_id' => 'required',
            'all' => 'required',
            'products' => 'nullable|json'
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        if (!Order::find($request->order_id)){
            return response()->json([
                'success' => false,
                'errors' => ['سفارشی با شناسه مورد نظر موجود نیست']
            ]);
        }

        ReturnProduct::create([
            'user_id' => auth()->id(),
            'order_id' => $request->order_id,
            'all' => (bool)$request->all,
            'products' => $request->products ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'مرجوع سفارش مورد نظر با موفقیت ثبت شد'
        ]);
    }
}
