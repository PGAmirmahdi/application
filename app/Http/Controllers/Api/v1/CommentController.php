<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function getComments(Request $request)
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

        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'errors' => 'محصولی با این شناسه وجود ندارد'
            ]);
        }

        return $product->comments()->where('status', 'accepted')->with(['user' => function($query) {
            $query->select('id','name', 'family');
        }])->latest()->paginate(10);
    }

    public function createComment(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'title' => 'required',
            'text' => 'required',
            'product_id' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $product = Product::find($request->product_id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'errors' => 'محصولی با این شناسه وجود ندارد'
            ]);
        }

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'title' => $request->title,
            'text' => $request->text,
            'favorite' => $request->favorite,
        ]);
        $totalfavorite = $product->comments()->sum('favorite');
        $countfavorite = $product->comments()->count('favorite');
        if ($countfavorite > 0) {
            $averagefavorite = $totalfavorite / $countfavorite;
        } else {
            $averagefavorite = 0;
        }
        $item=Product::where('id', $request->product_id)
            ->update(['favorites' => $averagefavorite]);
        return response()->json([
            'success' => true,
            'message' => 'نظر شما با موفقیت ثبت شد'
        ]);
    }
}
