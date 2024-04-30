<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\ReturnProduct;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index()
    {
        $returns = ReturnProduct::latest()->paginate(30);
        return view('panel.returns.index', compact('returns'));
    }

    public function show(ReturnProduct $return)
    {
        return view('panel.returns.show', compact('return'));
    }

    public function changeStatus(ReturnProduct $return)
    {
        if ($return->status == 'pending'){
            $return->update(['status' => 'returned']);
        }else{
            $return->update(['status' => 'pending']);
        }

        alert()->success('وضعیت سفارش مرجوعی با موفقیت تغییر کرد','تغییر وضعیت');
        return back();
    }
}
