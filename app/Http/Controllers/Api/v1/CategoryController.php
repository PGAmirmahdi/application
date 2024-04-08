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
            'category_id' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $category = Category::find($request->category_id);
        if (!$category){
            return response()->json([
                'success' => false,
                'errors' => ['دسته بندی مورد نظر پیدا نشد']
            ]);
        }

        return Product::where('category_id', $category->id)->latest()->paginate(10);
    }
}
