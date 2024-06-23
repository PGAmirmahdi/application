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
            'limit' => $request->order_limit,
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
        DB::beginTransaction();

        try {
            // Handle main image
            if ($request->hasFile('main_image')) {
                if ($product->main_image && file_exists(public_path($product->main_image))) {
                    unlink(public_path($product->main_image));
                }

                $main_image = upload_file($request->main_image, 'Products');
            } else {
                $main_image = $product->main_image;
            }

            // Handle images
            $images = [];
            if ($request->hasFile('images')) {
                if ($product->images) {
                    foreach (json_decode($product->images) as $image) {
                        if (file_exists(public_path($image))) {
                            unlink(public_path($image));
                        }
                    }
                }

                foreach ($request->images as $image) {
                    $uploadedImage = upload_file($image, 'Products');
                    $images[] = $uploadedImage;
                }
            } else {
                $images = json_decode($product->images) ?? [];
            }

            // Handle product properties
            $properties = [];
            if ($request->filled('keys') && $request->filled('values')) {
                foreach ($request->keys as $i => $key) {
                    $properties[] = [
                        'key' => $key,
                        'value' => $request->values[$i],
                    ];
                }
            }

            // Update product
            $product->update([
                'title' => $request->title,
                'code' => $request->code,
                'sku' => $request->sku,
                'price' => $request->price,
                'limit' => $request->order_limit,
                'description' => $request->description,
                'main_image' => $main_image,
                'images' => count($images) ? json_encode($images) : null,
                'category_id' => $request->category,
                'properties' => count($properties) ? json_encode($properties) : null,
            ]);

            DB::commit();

            alert()->success('محصول مورد نظر با موفقیت ویرایش شد','ویرایش محصول');
            return redirect()->route('products.index');

        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error('خطایی در ویرایش محصول رخ داد','ویرایش محصول');
            return redirect()->route('products.index')->with('error', 'Error updating product');
        }
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
