<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Role;
use App\Models\User;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PanelController extends Controller
{
    public function index(Request $request)
    {
        return view('panel.index');
    }

    public function readNotification($notification = null)
    {
        if ($notification == null){
            auth()->user()->unreadNotifications->markAsRead();
            return back();
        }

        $notif = auth()->user()->unreadNotifications()->whereId($notification)->first();
        if (!$notif){
            return back();
        }

        $notif->markAsRead();
        return redirect()->to($notif->data['url']);
    }

    public function login(Request $request)
    {
        if ($request->method() == 'GET'){
            $users = User::where('id', '!=', auth()->id())->whereIn('id', [3, 4, 152])->get(['id','name','family']);

            return view('panel.login', compact('users'));
        }

        Auth::loginUsingId($request->user);
        return redirect()->route('panel');
    }

    public function saveFCMToken(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'token' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        auth()->user()->update(['fcm_token' => $request->token]);

        return response()->json([
            'success' => true,
            'message' => 'token saved successfully.',
        ]);
    }
}
