<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        return view('panel.banners.index');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'images' => 'required',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2000',
        ],[
            'images.*.image' => 'فایل های انتخابی باید از نوع تصویر باشند',
            'images.*.mimes' => 'فرمت تصاویر انتخابی باید از این نوع باشند: jpg,jpeg,png',
            'images.*.max' => 'اندازه تصاویر انتخابی نباید بیشتر از 2 مگ باشد',
        ]);

        foreach (Banner::all() as $banner){
            unlink(public_path($banner->path));
            $banner->delete();
        }

        foreach ($request->images as $image){
            Banner::create([
                'name' => $image->getClientOriginalName(),
                'size' => $image->getSize(),
                'type' => $image->getClientOriginalExtension(),
                'path' => upload_file($image, 'Banner'),
            ]);
        }

        alert()->success('بنر های صفحه اصلی با موفقیت تغییر کردند','تغییر بنر ها');
        return back();
    }
}
