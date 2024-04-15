<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Notifications\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

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

        // send notification
        if ($comment->status == 'accepted'){
            $message = 'نظر شما توسط مدیر تایید شد';
            $url = route('comments.index');
            $user = $comment->user;

            Notification::send($user, new SendMessage($message, $url));
        }elseif ($comment->status == 'rejected'){
            $message = 'نظر شما توسط مدیر رد شد';
            $url = route('comments.index');
            $user = $comment->user;

            Notification::send($user, new SendMessage($message, $url));
        }
        // end send notification

        return back();
    }
}
