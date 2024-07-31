<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

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
}
