<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; //
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "avatar" => "image|mimes:jpg,jpeg,png,gif,svg|max:1024|dimensions:width=128,height=128" // verify if that upload file is image get from <input name="thumbnail"/>, only accept jpg jpeg, and only 1mb allow, dimension can put max_height, height, width and ratio:3/2
        ];
    }
}
