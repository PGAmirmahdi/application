<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\GuideVideos;
use App\Models\Product;
use Illuminate\Http\Request;

class GuideVideosController extends Controller
{
    public function getGuideVideos(Request $request)
    {
        $validate = validator()->make($request->all(), [
            'product_id' => 'required',
        ]);


        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $product_id = $request->product_id;

        $item = GuideVideos::where('guide_videos.id', $product_id)
            ->join('products', 'guide_videos.product_id', '=', 'products.id')
            ->select([
                'guide_videos.title as guide_title',
                'guide_videos.text',
                'guide_videos.main_video',
                'products.title as product_title'
            ])
            ->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'data' => [

                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'اطلاعات داده شد',
            'data' => [$item]
        ]);
    }
}
