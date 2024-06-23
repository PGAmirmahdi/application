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
        $product = Product::find($request->product_id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'errors' => 'محصولی با این شناسه وجود ندارد'
            ]);
        }

        // Handle the main video upload
        $main_video_file = $request->file('main_video');
        if ($main_video_file) {
            $main_video = upload_file($main_video_file, 'GuideVideo');
        } else {
            return response()->json([
                'success' => false,
                'errors' => 'ویدئوی اصلی ارسال نشده است'
            ]);
        }

        // Create GuideVideo
        GuideVideos::create([
            'title' => $request->title,
            'text' => $request->text,
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
            'name' => $main_video_file->getClientOriginalName(),
            'size' => $main_video_file->getSize(),
            'type' => $main_video_file->getClientOriginalExtension(),
            'main_video'=> $main_video,
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
        if ($request->hasFile('main_video')) {
            if ($videos->main_video) {
                unlink(public_path($videos->main_video));
            }

            $main_video_file = $request->file('main_video');
            $main_video = upload_file($main_video_file, 'GuideVideo');
        } else {
            $main_video = $videos->main_video;
        }

        $item2 = [
            'title' => $request->title,
            'text' => $request->text,
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
            'main_video' => $main_video,
        ];
        if ($request->hasFile('main_video')) {
            $item2['name'] = $main_video_file->getClientOriginalName();
            $item2['size'] = $main_video_file->getSize();
            $item2['type'] = $main_video_file->getClientOriginalExtension();
        }

        $videos->where('id', $id)->update($item2);

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
