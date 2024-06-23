<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\StoreCouponsRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Http\Requests\UpdateCouponsRequest;
use App\Models\Coupon;
use App\Models\Coupons;
use Illuminate\Http\Request;

class CouponsController extends Controller
{
    public function index()
    {
        $coupons = Coupons::latest()->paginate(30);
        return view('panel.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('panel.coupons.create');
    }

    public function store(StoreCouponsRequest $request)
    {
        Coupons::create([
            'title' => $request->title,
            'code' => $request->code,
            'amount_pc' => $request->amount_pc,
        ]);

        alert()->success('کد تخفیف مورد نظر با موفقیت ایجاد شد','ایجاد کد تخفیف');
        return redirect()->route('coupons.index');
    }

    public function show($id)
    {
        //
    }

    public function edit(Coupons $coupon)
    {
        return view('panel.coupons.edit', compact('coupon'));

    }

    public function update(UpdateCouponsRequest $request, Coupons $coupon)
    {
        $coupon->update([
            'title' => $request->title,
            'code' => $request->code,
            'amount_pc' => $request->amount_pc,
        ]);

        alert()->success('کد تخفیف مورد نظر با موفقیت ویرایش شد','ویرایش کد تخفیف');
        return redirect()->route('coupons.index');
    }

    public function destroy(Coupons $coupon)
    {
        $this->authorize('coupons-delete');

        $coupon->delete();
        return back();
    }
}
