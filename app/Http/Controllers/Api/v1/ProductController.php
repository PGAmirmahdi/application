<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function getProducts()
    {
        // log the user
        if (!Log::where(['activity_name' => 'visit', 'ip' => \request()->ip()])->exists()){
            activity_log('visit', __METHOD__);
        }

        return Product::latest()->paginate(10);
    }

    public function search(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'title' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        return Product::where('title', 'like', '%' . $request->title . '%')->latest()->paginate(10);
    }

    public function filter(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'sortBy' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $category_id = $request->category_id;

        switch ($request->sortBy)
        {
            case 'cheapest':
                if ($category_id){
                    return Product::where('category_id', $category_id)->orderBy('price')->paginate(10);
                }

                return Product::orderBy('price')->paginate(10);
            case 'expensive':
                if ($category_id){
                    return Product::where('category_id', $category_id)->orderByDesc('price')->paginate(10);
                }

                return Product::orderByDesc('price')->paginate(10);
            case 'bestselling':
                $orders_id = Payment::where('status','success')->pluck('order_id');

                $orders = OrderItem::whereIn('order_id', $orders_id)
                    ->select('product_id', DB::raw('COUNT(*) as count'))
                    ->groupBy('product_id');

                if ($category_id){
                    $products = Product::where('category_id', $category_id)->joinSub($orders, 'orders', function ($join) {
                        $join->on('products.id', '=', 'orders.product_id');
                    })
                        ->orderByDesc('count')
                        ->paginate(10);

                    return $products;
                }

                $products = Product::joinSub($orders, 'orders', function ($join) {
                    $join->on('products.id', '=', 'orders.product_id');
                })
                    ->orderByDesc('count')
                    ->paginate(10);

                return $products;
            default:
                return response()->json([
                    'success' => false,
                    'errors' => ['یکی از 3 مقدار cheapest, expensive و یا bestselling الزامی است']
                ]);
        }
    }
}
