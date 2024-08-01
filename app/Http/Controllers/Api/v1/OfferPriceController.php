<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OfferPriceController extends Controller
{
    public function getPrice($id)
    {
        $product = Product::find($id);
        if ($product) {
            return response()->json(['success' => true, 'price' => $product->price]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function getOffer(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->messages(),
            ]);
        }
        $product_id= $request->product_id;
        $item = Offer::where('offers.product_id', $product_id)
            ->join('products', 'offers.product_id', '=', 'products.id')
            ->select([
                'offers.product_id as product_id',
                'products.title as product_title',
                'products.code as product_code',
                'products.sku as product_sku',
                'offers.description as description',
                'offers.percentage as percentage',
                'offers.price_before as price_before',
                'offers.price_after as price_after',
            ])
            ->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'هیچ اطلاعاتی یافت نشد',
                'data' => []
            ], 404); // استفاده از کد وضعیت HTTP مناسب
        }

        return response()->json([
            'success' => true,
            'message' => 'اطلاعات با موفقیت دریافت شد',
            'data' => $item
        ]);
    }
}
