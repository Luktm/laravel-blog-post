<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePost extends FormRequest
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
            // bail immedately stop
            'title' => 'bail|required|min:5|max:100',
            'content' => 'required|min:10',
            "thumbnail" => "image|mimes:jpg,jpeg,png,gif,svg|max:1024|dimensions:min_height=500" // verify if that upload file is image get from <input name="thumbnail"/>, only accept jpg jpeg, and only 1mb allow, dimension can put max_height, height, width and ratio:3/2
        ];
    }
}
