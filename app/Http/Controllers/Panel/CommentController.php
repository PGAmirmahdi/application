<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::latest()->paginate(30);
        return view('panel.comments.index', compact('comments'));
    }

    public function changeStatus(Request $request)
    {
        $comment = Comment::find($request->comment_id);
        $status = $request->comment_status;

        $comment->update(['status' => $status]);

        return back();
    }
}
