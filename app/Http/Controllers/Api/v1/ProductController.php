<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProducts()
    {
        // log the user
        activity_log('visit', __METHOD__);

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
}
