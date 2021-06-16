<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
{
    /**
     * * php artisan make:request UpdateUser
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
    // ? luk: key always is column of user table
    public function rules()
    {
        return [
            "avatar" => "image|mimes:jpg,jpeg,png,gif,svg|max:1024|dimensions:width=128,height=128", // verify if that upload file is image get from <input name="thumbnail"/>, only accept jpg jpeg, and only 1mb allow, dimension can put max_height, height, width and ratio:3/2
            "locale" => [
                "required", // must select
                Rule::in(array_keys(User::LOCALES)) // laravel own class, in() will check the key in the column or not, to get the User.php LOCALE variable key, use array_key
            ]
        ];
    }
}
