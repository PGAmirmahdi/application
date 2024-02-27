<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function getFavorites()
    {
        return auth()->user()->favorites()->with('product')->get();
    }

    public function toggleFavorite(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'product_id' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $favorite = auth()->user()->favorites()->where(['user_id' => auth()->id(), 'product_id' => $request->product_id]);
        if ($favorite->first()){
            $favorite->delete();

            $status = true;
            $message = 'این محصول از علاقه مندی های شما حذف شد';
        }else{
            $favorite->create(['product_id' => $request->product_id]);

            $status = true;
            $message = 'این محصول به علاقه مندی های شما اضافه شد';
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function checkFavorite(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'product_id' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $is_favorite = (bool)auth()->user()->favorites()->where(['user_id' => auth()->id(), 'product_id' => $request->product_id])->first();

        return response()->json([
            'success' => true,
            'is_favorite' => $is_favorite
        ]);
    }
}
