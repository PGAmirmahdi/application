<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Banner_mid;
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
    public function upload_mid(Request $request)
    {
        $request->validate([
            'images_mid' => 'required',
            'images_mid.*' => 'image|mimes:jpg,jpeg,png|max:2000',
        ],[
            'images_mid.*.image' => 'فایل های انتخابی باید از نوع تصویر باشند',
            'images_mid.*.mimes' => 'فرمت تصاویر انتخابی باید از این نوع باشند: jpg,jpeg,png',
            'images_mid.*.max' => 'اندازه تصاویر انتخابی نباید بیشتر از 2 مگ باشد',
        ]);

        foreach (Banner_mid::all() as $banner_mid){
            unlink(public_path($banner_mid->path));
            $banner_mid->delete();
        }

        foreach ($request->images_mid as $image){
            Banner_mid::create([
                'name' => $image->getClientOriginalName(),
                'size' => $image->getSize(),
                'type' => $image->getClientOriginalExtension(),
                'path' => upload_file($image, 'Banner'),
            ]);
        }

        alert()->success('بنر های میانی صفحه اصلی با موفقیت تغییر کردند','تغییر بنر های میانی');
        return back();
    }
}
