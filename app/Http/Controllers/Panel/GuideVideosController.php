<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGuideVideosRequest;
use App\Http\Requests\UpdateGuideVideosRequest;
use App\Models\GuideVideos;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuideVideosController extends Controller
{
    public function index()
    {
        $videos = GuideVideos::latest()->paginate(30);
        return view('panel.GuideVideos.index', compact('videos'));
    }

    public function create()
    {
        return view('panel.GuideVideos.create');
    }

    public function store(StoreGuideVideosRequest $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'title' => 'required|string|max:255',
            'main_video' => 'required|file|mimes:mp4,avi,mov|max:10240',
            'text' => 'required|string',
        ]);
        $product = Product::find($request->product_id);
        if (!$product) {
            dd($product);
//            return response()->json([
//                'success' => false,
//                'errors' => 'محصولی با این شناسه وجود ندارد'
//            ]);
        }
        $main_video = upload_file($request->main_video, 'GuideVideo');

        // videos
//        $images = [];
//        if ($request->videos){
//            foreach ($request->videos as $video){
//                $video = upload_file($video, 'GuideVideos');
//                $videos[] = $video;
//            }
//        }
        // end videos
        GuideVideos::create([
            'title' => $request->title,
            'text' => $request->text,
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
            'main_video'=> $main_video
//            'category_id' => $request->category,
        ]);

        alert()->success('ویدئو با موفقیت آپلود شدند', 'آپلود ویدئو ها');
        return response()->json(['success' => 'فایل با موفقیت آپلود شد']);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $video = GuideVideos::findOrFail($id);
        return view('panel.GuideVideos.edit', compact('video'));
    }

    public function update(UpdateGuideVideosRequest $request,  GuideVideos $videos,$id)
    {
        if ($request->main_video){
            if ($videos->main_video){
                unlink(public_path($videos->main_video));
            }

            $main_video = upload_file($request->main_video, 'GuideVideo');
        }else{
            $main_video = $videos->main_video;
        }
        $item2=[
            'title' => $request->title,
            'text' => $request->text,
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
            'main_video'=> $main_video
        ];
        $item= $videos::query()->where('id', $id)->update($item2);
        alert()->success('ویدئو مورد نظر با موفقیت ویرایش شد','ویرایش ویدئو');
        return response()->json(['success' => 'فایل با موفقیت آپلود شد']);
    }

    public function destroy(GuideVideos $videos,$id)
    {
        if ($videos->main_video){
            unlink(public_path($videos->main_video));
        }
        $videos::query()->where('id', $id)->delete();
        return back();
    }
    public function search(Request $request)
    {
        $videos = GuideVideos::where('title', 'like', "%$request->title%")->when($request->product_id, function ($query) use ($request) {
            return $query->where('product_id', $request->product_id);
        })->latest()->paginate(30);

        return view('panel.GuideVideos.index', compact('videos'));
    }
}
