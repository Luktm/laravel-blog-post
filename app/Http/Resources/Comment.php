<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CommentUser as CommentUserResource;

class Comment extends JsonResource
{
    /**
     * * php artisan make:resource Comment
     * docs to visit https://laravel.com/docs/8.x/eloquent-resources
     * Transform the resource into an array.
     * Remember use the Resource suffix in Controller
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        // we change change the name key here
        // $this from comment model keyword laravel know to get from construtor in resource
        return [
            "comment_id" => $this->id,
            "content" => $this->content,
            "created_at" => (string)$this->created_at, // cascade (string) only, it's convert to the string only
            "updated_at" => (string)$this->updated_at,
            // nested naive way, not efficient way
            // "user" => [
            //     "id" => $this->user->id,
            // ]

            // an instantiate must call new keyword
            // there is method in resource to use, call $this->whenLoaded("relation name is User.php user() method");
            // * Retrieve a relationship if it has been loaded.
            // to have user in json, we have to call $post->comments()->with("user")->get() in controller
            // * remember if wanted to call more method must use () like $post->comments to $post->comments()
            "user" => new CommentUserResource($this->whenLoaded("user")), // this method like flutter and react native typescript interface

        ];

        // * it return { "data: {"id": "value"} } or specify data i'd input
    }
}
