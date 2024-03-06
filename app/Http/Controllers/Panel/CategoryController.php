<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $parent_id = \request()->parent_id;
        if ($parent_id){
            $categories = Category::where('parent_id', $parent_id)->latest()->paginate(30);
        }else{
            $categories = Category::whereNull('parent_id')->latest()->paginate(30);
        }

        return view('panel.categories.index', compact('categories'));
    }

    public function create()
    {
        $parent_id = \request()->parent_id;
        return view('panel.categories.create', compact('parent_id'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $parent_id = \request()->parent_id;

        Category::create([
            'name' => $request->name,
            'parent_id' => $request->parent_category,
        ]);

        alert()->success('دسته بندی با موفقیت ایجاد شد','ایجاد دسته بندی');
        return redirect()->route('categories.index',['parent_id' => $parent_id]);
    }

    public function show(Category $category)
    {
        //
    }

    public function edit(Category $category)
    {
        $categories = \App\Models\Category::where('id','!=',$category->id)->where(function ($query) use ($category) {
            $query->where('parent_id', '!=', $category->id)
                ->orWhereNull('parent_id');
        })->get();

        return view('panel.categories.edit', compact('category','categories'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update([
            'name' => $request->name,
            'parent_id' => $request->parent_category,
        ]);

        alert()->success('دسته بندی با موفقیت ویرایش شد','ویرایش دسته بندی');
        return redirect()->route('categories.index', ['parent_id' => $category->parent_id]);
    }

    public function destroy(Category $category)
    {
        if ($category->children()->has('products')->exists() || $category->products()->exists()){
            return response('دسته بندی مورد نظر یا زیردسته های آن در محصولات مورد استفاده قرار گرفته اند',500);
        }

        $category->children()->delete();
        $category->delete();

        return back();
    }
}
