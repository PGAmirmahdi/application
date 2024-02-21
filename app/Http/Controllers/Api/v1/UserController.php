<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Code;
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

        sendSMS(196667, $user->phone, [$code]);

        return response()->json([
            'message' => 'user created successfully!',
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
        $code = (string)random_int(10000, 99999);
        $user = User::wherePhone($request->phone)->first();

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

        if ($success){
            $user->update([
                'phone_code' => $code,
                'phone_expire' => now()->addMinutes(2)
            ]);

            sendSMS(196667, $user->phone, [$code]);
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    public function login(Request $request)
    {
        $user = User::wherePhone($request->phone)->first();
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

        return response()->json([
            'success' => $success,
            'message' => $message,
            'token' => $token,
        ]);
    }
}
