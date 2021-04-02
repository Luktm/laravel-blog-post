<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class PostTagController extends Controller
{
    // php artisan make:controller PostTagController
    // seperate from PostsController for managible and maintainable, but this index supposed to pass in tagId like show(id)
    // and define the route in web.php
    public function index($tag) {
        $tag = Tag::findOrFail($tag);

        // $tag->blogPosts was from Tag.php related model as "function blogPosts() {return $this->belongsToMany();}"
        // this will fetch all tag associate with particular blogPost
        return view("posts.index", [
            "posts" => $tag->blogPosts,
            "mostCommented" => [],
            "mostActive" => [],
            "mostActiveLastMonth" => []
            ]
        );
    }
}
