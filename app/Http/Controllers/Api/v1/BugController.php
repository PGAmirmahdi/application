<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Bug;
use App\Models\Comment;
use Illuminate\Http\Request;

class BugController extends Controller
{
    public function bugReport(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'title' => 'required',
            'description' => 'required',
            'person' => 'required',
            'phone' => 'required',
            'app_version' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        Bug::create([
            'title' => $request->title,
            'description' => $request->description,
            'person' => $request->person,
            'phone' => $request->phone,
            'app_version' => $request->app_version,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'گزارش خرابی با موفقیت ثبت شد'
        ]);
    }
}
