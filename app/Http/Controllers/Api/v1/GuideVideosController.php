<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\GuideVideos;
use Illuminate\Http\Request;

class GuideVideosController extends Controller
{
    public function getGuideVideos()
    {
        GuideVideos::all();
    }
}
