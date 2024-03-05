<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(30);
        return view('panel.products.index', compact('products'));
    }

    public function create()
    {
        return view('panel.products.create');
    }

    public function store(StoreProductRequest $request)
    {
        $main_image = upload_file($request->main_image, 'Products');

        // images
        $images = [];
        if ($request->images){
            foreach ($request->images as $image){
                $image = upload_file($image, 'Products');
                $images[] = $image;
            }
        }
        // end images

        // product properties
        $properties = [];
        if ($request->keys){
            foreach ($request->keys as $i => $key){
                $properties[] = [
                    'key' => $key,
                    'value' => $request->values[$i],
                ];
            }
        }
        // end product properties

        // create product
        Product::create([
            'title' => $request->title,
            'code' => $request->code,
            'sku' => $request->sku,
            'price' => $request->price,
            'description' => $request->description,
            'main_image' => $main_image,
            'images' => count($images) ? json_encode($images) : null,
            'category_id' => $request->category,
            'properties' => count($properties) ? json_encode($properties) : null,
        ]);

        alert()->success('محصول مورد نظر با موفقیت ایجاد شد','ایجاد محصول');
        return redirect()->route('products.index');
    }

    public function show(Product $product)
    {
        //
    }

    public function edit(Product $product)
    {
        return view('panel.products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        if ($request->main_image){
            unlink(public_path($product->main_image));

            $main_image = upload_file($request->main_image, 'Products');
        }else{
            $main_image = $product->main_image;
        }

        // images
        $images = [];
        if ($request->images){
            if ($product->images){
                foreach (json_decode($product->images) as $image){
                    unlink(public_path($image));
                }
            }

            foreach ($request->images as $image){
                $image = upload_file($image, 'Products');
                $images[] = $image;
            }
        }else{
            $images = json_decode($product->images) ?? [];
        }
        // end images

        // product properties
        $properties = [];
        if ($request->keys){
            foreach ($request->keys as $i => $key){
                $properties[] = [
                    'key' => $key,
                    'value' => $request->values[$i],
                ];
            }
        }
        // end product properties

        // create product
        $product->update([
            'title' => $request->title,
            'code' => $request->code,
            'sku' => $request->sku,
            'price' => $request->price,
            'description' => $request->description,
            'main_image' => $main_image,
            'images' => count($images) ? json_encode($images) : null,
            'category_id' => $request->category,
            'properties' => count($properties) ? json_encode($properties) : null,
        ]);

        alert()->success('محصول مورد نظر با موفقیت ویرایش شد','ویرایش محصول');
        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        if ($product->main_image){
            unlink(public_path($product->main_image));
        }

        if ($product->images){
            foreach (json_decode($product->images) as $image){
                unlink(public_path($image));
            }
        }

        $product->delete();
        return back();
    }

    public function search(Request $request)
    {
        $products = Product::where('title', 'like', "%$request->title%")->when($request->code, function ($query) use ($request) {
            return $query->where('code', $request->code);
        })->latest()->paginate(30);

        return view('panel.products.index', compact('products'));
    }

}
