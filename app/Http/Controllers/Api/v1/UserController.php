<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Code;
use App\Models\Province;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request){
        $registerUserData = $request->validate([
            'name' => 'required',
            'family' => 'required',
            'phone' => 'required|unique:users',
        ]);

        $user = User::create([
            'name' => $registerUserData['name'],
            'family' => $registerUserData['family'],
            'phone' => $registerUserData['phone'],
        ]);

        // send code
        $code = (string)random_int(10000, 99999);
        $user->update([
            'phone_code' => $code,
            'phone_expire' => now()->addMinutes(2)
        ]);
        // end send code

        sendSMS(201523, $user->phone, [$code]);

        return response()->json([
            'message' => 'user created successfully!',
        ]);
    }

    public function login(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'phone' => 'required',
            'code' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $user = User::wherePhone($request->phone)->first();

        if (!$user){
            $success = false;
            $message = 'کاربری با این شماره موبایل وجود ندارد';
            $token = null;
        }else{
            if ($user->phone_code){
                if ($user->phone_code == $request->code && $user->phone_expire > now()->toDateTimeString()){
                    $success = true;
                    $message = 'با موفقیت وارد شدید';
                    $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;
                }else{
                    $success = false;
                    $message = 'کد وارد شده معتبر نیست';
                    $token = null;
                }
            }else{
                $success = false;
                $message = 'کد وارد شده معتبر نیست';
                $token = null;
            }
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'token' => $token,
        ]);
    }

    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json([
            "message" => "logged out"
        ]);
    }

    public function sendCode(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'phone' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $code = (string)random_int(10000, 99999);
        $user = User::wherePhone($request->phone)->first();

        if (!$user){
            $success = false;
            $message = 'کاربری با این شماره موبایل وجود ندارد';
        }else{
            if ($user->phone_code){
                if ($user->phone_expire < now()->toDateTimeString()){
                    $success = true;
                    $message = 'کد تایید با موفقیت ارسال شد';
                }else{
                    $success = false;
                    $message = 'دقایقی دیگر تلاش کنید';
                }
            }else{
                $success = true;
                $message = 'کد تایید با موفقیت ارسال شد';
            }
        }

        if ($success){
            $user->update([
                'phone_code' => $code,
                'phone_expire' => now()->addMinutes(2)
            ]);

            sendSMS(201523, $user->phone, [$code]);
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    public function getProfile()
    {
        return auth()->user();
    }

    public function editProfile(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'name' => 'required',
            'family' => 'required',
            'national_code' => 'nullable|unique:users,national_code,'.auth()->id(),
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        auth()->user()->update([
            'name' => $request->name,
            'family' => $request->family,
            'national_code' => $request->national_code,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'اطلاعات شما با موفقیت ویرایش شد'
        ]);
    }

    public function getNotifications()
    {
        return auth()->user()->notifications()->latest()->paginate(10);
    }

    public function readNotification(Request $request)
    {
        if ($request->id == null){
            auth()->user()->unreadNotifications->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'همه اعلانات خوانده شدند',
            ]);
        }

        $notif = auth()->user()->unreadNotifications()->whereId($request->id)->first();
        if (!$notif){
            return response()->json([
                'success' => false,
                'errors' => ['اعلان خوانده نشده ای با این شناسه وجو ندارد'],
            ]);
        }

        $notif->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'اعلان مورد نظر خوانده شد',
        ]);
    }
}
