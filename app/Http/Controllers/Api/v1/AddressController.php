<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function getAddresses()
    {
        return auth()->user()->addresses;
    }

    public function addAddress(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'title' => 'required',
            'province_id' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
            'full_address' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $address = auth()->user()->addresses()->where('title', $request->title);

        if ($address->first()){
            $success = false;
            $message = 'شما قبلا آدرسی با این نام افزوده اید';
        }else{
            $address->create([
                'title' => $request->title,
                'province_id' => $request->province_id,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'full_address' => $request->full_address,
            ]);

            $success = true;
            $message = 'آدرس با موفقیت افزوده شد';
        }

        return response()->json([
            'success' => $success,
            'message' => $message
        ]);
    }

    public function editAddress(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'address_id' => 'required',
            'title' => 'required',
            'province_id' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
            'full_address' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $address = auth()->user()->addresses()->whereId($request->address_id);
        if ($address->first()) {
            $address->update([
                'title' => $request->title,
                'province_id' => $request->province_id,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'full_address' => $request->full_address,
            ]);

            $success = true;
            $message = 'آدرس با موفقیت ویرایش شد';
        }else{
            $success = false;
            $message = 'آدرس مورد نظر موجود نیست';
        }

        return response()->json([
            'success' => $success,
            'message' => $message
        ]);
    }

    public function deleteAddress(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'address_id' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $address = Address::find($request->address_id);

        if ($address){
            $address->delete();

            $success = true;
            $message = 'آدرس با موفقیت حذف شد';
        }else{
            $success = false;
            $message = 'آدرس مورد نظر وجود ندارد';
        }
        return response()->json([
            'success' => $success,
            'message' => $message
        ]);
    }
}
