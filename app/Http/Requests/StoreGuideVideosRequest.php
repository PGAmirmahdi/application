<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuideVideosRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id',
            'title' => 'required',
            'main_video' => 'nullable|file|mimes:mp4,mov,avi|max:102400',
            'text' => 'required',
        ];
    }

    public function messages()
    {
        return[
            'main_video.*.video' => 'فایل های انتخابی باید از نوع ویدئو باشند',
            'main_video.*.max' => 'اندازه ویدئو انتخابی نباید بیشتر از 2 مگ باشد',
        ];
    }
}
