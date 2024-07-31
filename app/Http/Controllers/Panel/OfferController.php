<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\OfferRequest;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $offers = Offer::query();

        // Join with products table
        $offers->join('products', 'offers.product_id', '=', 'products.id');

        if ($request->filled('tracking_code')) {
            $offers->where('products.title', 'like', '%' . $request->tracking_code . '%');
        }

        if ($request->filled('sku')) {
            $offers->where('products.sku', 'like', '%' . $request->sku . '%');
        }

        if ($request->filled('code')) {
            $offers->where('products.code', 'like', '%' . $request->code . '%');
        }

        if ($request->filled('category_name')) {
            $offers->whereHas('products.category', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->category_name . '%');
            });
        }

        if ($request->filled('percentage')) {
            $offers->where('offers.percentage', $request->percentage);
        }

        // Paginate the results
        $offers = $offers->select('offers.*')->latest()->paginate(20);

        return view('panel.offers.index', compact('offers'));
    }

    public function create()
    {
        $products = Product::doesntHave('offers')->get();
        return view('panel.offers.create',compact('products'));
    }

    public function store(OfferRequest $request)
    {
        // ابتدا یک پیشنهاد جدید ایجاد کنید
        $offer = Offer::create([
            'percentage' => $request->percentage,
            'price_before' => $request->price_before,
            'description' => $request->description,
            'price_after' => $request->price_after,
        ]);

        // سپس محصول مربوطه را با offer_id به‌روزرسانی کنید
        Product::where('id', $request->product_id)->update(['offer_id' => $offer->id]);

        alert()->success('تخفیف مورد نظر با موفقیت ایجاد شد', 'ایجاد تخفیف');
        return redirect()->route('offers.index');
    }

    public function show($id)
    {
        //
    }

    public function edit(Offer $offer)
    {
        $products = Product::doesntHave('offers')->orWhere('id', $offer->product_id)->get();
        return view('panel.offers.edit', compact('offer', 'products'));
    }

    public function update(OfferRequest $request, Offer $offer)
    {
        $offer->update([
            'percentage' => $request->percentage,
            'price_before' => $request->price_before,
            'description' => $request->description,
            'price_after' => $request->price_after,
            'product_id' => $request->product_id,
        ]);

        Product::where('id', $request->product_id)->update(['offer_id' => $offer->id]);

        alert()->success('تخفیف مورد نظر با موفقیت ویرایش شد', 'ویرایش تخفیف');
        return redirect()->route('offers.index');
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();
        return back();
    }
}
