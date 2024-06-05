<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Banner_mid;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function getBanners()
    {
        return Banner::all();
    }
    public function getBanners_mid()
    {
        return Banner_mid::all();
    }
}
