<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getCategories()
    {
        return CategoryResource::collection(Category::whereNull('parent_id')->get());
    }

    public function getChildren(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'parent_id' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $category = Category::find($request->parent_id);
        if (!$category){
            return response()->json([
                'success' => false,
                'errors' => ['دسته بندی مورد نظر پیدا نشد']
            ]);
        }

        return CategoryResource::collection($category->children);
    }

    public function getProducts(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $category_id = $request->category_id;

        // بررسی وجود دسته‌بندی
        $category = Category::find($category_id);
        if (!$category){
            return response()->json([
                'success' => false,
                'errors' => ['دسته بندی مورد نظر پیدا نشد']
            ]);
        }

        // ساخت کوئری برای دریافت محصولات همراه با اطلاعات آفر
        $query = Product::leftJoin('offers', 'products.id', '=', 'offers.product_id')
            ->select(
                'products.*',
                DB::raw('COALESCE(offers.price_after, products.price) as effective_price'),
                'offers.description as offer_description',
                'offers.percentage as offer_percentage',
                'offers.price_before as offer_price_before',
                'offers.price_after as offer_price_after'
            )
            ->where('products.category_id', $category_id)
            ->latest();

        // اجرای کوئری و برگرداندن نتایج
        $products = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

}
