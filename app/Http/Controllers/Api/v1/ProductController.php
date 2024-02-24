<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProducts()
    {
        return Product::latest()->paginate(10);
    }

    public function search(Request $request)
    {
        return Product::where('title', 'like', '%' . $request->search . '%')->latest()->paginate(10);
    }
}
