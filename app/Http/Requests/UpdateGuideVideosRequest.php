<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGuideVideosRequest extends FormRequest
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
            'text' => 'required',
            'product_id' => 'required',
            'main_video' => 'required',
            'main_video.*' => 'video|mimes:mp4,avi,mov,mpeg|max:100000',
        ];
    }
    public function messages()
    {
        return [
            'main_video.*.video' => 'فایل های انتخابی باید از نوع ویدئو باشند',
            'main_video.*.mimes' => 'فرمت ویدئو انتخابی باید از این نوع باشند: mp4,avi,mov,mpeg',
            'main_video.*.max' => 'اندازه ویدئو انتخابی نباید بیشتر از 2 مگ باشد',
        ];
    }
}
