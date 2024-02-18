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
            'password' => 'required|min:8'
        ]);

        $user = User::create([
            'name' => $registerUserData['name'],
            'family' => $registerUserData['family'],
            'phone' => $registerUserData['phone'],
            'password' => Hash::make($registerUserData['password']),
        ]);

        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;

        return response()->json([
            'message' => 'user created successfully!',
            'access_token' => $token,
        ]);
    }

    public function login(Request $request){
        $loginUserData = $request->validate([
            'phone' => 'required',
            'password' => 'required|min:8'
        ]);

        $user = User::where('phone', $loginUserData['phone'])->first();

        if(!$user || !Hash::check($loginUserData['password'], $user->password)){
            return response()->json([
                'message' => 'Invalid Credentials'
            ],401);
        }
        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
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
        $user = auth()->user();

        if ($user->code){
            if ($user->code->phone_expire < now()->toDateTimeString()){
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
            $user->code()->create([
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
}
