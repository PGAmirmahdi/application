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

        $query = Product::leftJoin('offers', 'products.id', '=', 'offers.product_id')
            ->select('products.*',
                DB::raw('COALESCE(offers.price_after, products.price) as effective_price'),
                'offers.description as offer_description',
                'offers.percentage as percentage',
                'offers.price_before as price_before',
                'offers.price_after as price_after')
            ->where('products.title', 'like', '%' . $request->title . '%')
            ->latest();

        $products = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function filter(Request $request)
    {
        $validate = validator()->make($request->all(), [
            'sortBy' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $category_id = $request->category_id;

        // ساخت یک کوئری پایه برای محصولات
        $query = Product::leftJoin('offers', 'products.id', '=', 'offers.product_id')
            ->select('products.*',
                DB::raw('COALESCE(offers.price_after, products.price) as effective_price'),
                'products.description as description',
                'offers.percentage as percentage',
                'offers.price_before as price_before',
                'offers.price_after as price_after');

        if ($category_id) {
            $query->where('products.category_id', $category_id);
        }

        switch ($request->sortBy) {
            case 'cheapest':
                $query->orderBy('effective_price');
                break;

            case 'favorites':
                $query->orderByDesc('products.favorites');
                break;

            case 'expensive':
                $query->orderByDesc('effective_price');
                break;

            case 'bestselling':
                $orders_id = Payment::where('status', 'success')->pluck('order_id');
                $orders = OrderItem::whereIn('order_id', $orders_id)
                    ->select('product_id', DB::raw('COUNT(*) as count'))
                    ->groupBy('product_id');

                $query = $query->joinSub($orders, 'orders', function ($join) {
                    $join->on('products.id', '=', 'orders.product_id');
                })->orderByDesc('count');
                break;

            default:
                return response()->json([
                    'success' => false,
                    'errors' => ['یکی از 4 مقدار cheapest, expensive, favorites و یا bestselling الزامی است']
                ]);
        }

        // اجرای کوئری و برگرداندن نتایج
        $products = $query->orderByDesc('products.id')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

}
