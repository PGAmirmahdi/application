<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'code' => 'required|numeric',
            'category' => 'required',
            'price' => 'required',
            'order_limit' => 'required|numeric',
            'main_image' => 'required|mimes:jpg,jpeg,png|max:3000',
            'images.*' => 'nullable|mimes:jpg,jpeg,png|max:3000',
            'description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'images.*.mimes' => 'فرمت تصاویر انتخابی باید از این نوع باشند: jpg,jpeg,png',
            'images.*.max' => 'اندازه تصاویر انتخابی نباید بیشتر از 3 مگ باشد',
        ];
    }
}
