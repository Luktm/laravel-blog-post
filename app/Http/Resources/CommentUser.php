<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CommentUser extends JsonResource
{
    /**
     * * php artisan make:resource CommentUser
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        // if use this method, please use ::collection in controller
        return [
            "id" => $this->id,
            "name" => $this->name,
            // check $this->when(Auth::user()->is_admin) condition of is_admin, then return email, but since we are not authenticated, so just put true instead
            "email" => $this->when(true, $this->email),
        ];
    }
}
