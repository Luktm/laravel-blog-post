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
            // to reduce amount of query executed change $tag->blogPosts to $tag->blogPosts()->...

            // episode 179 we are accessing the relation as the method $tag->blogPosts()

            // latestWithRelations get from BlogPost.php at line 106
            "posts" => $tag->blogPosts()
                ->latestWithRelations()
                ->get()
                // ->latest()
                // ->withCount("comments")
                // ->with("user")
                // ->with("tags")
                // ->get(),
                // but we can actually create public function scopeLatestWithRelations in BlogPost.php
            // "mostCommented" => [],
            // "mostActive" => [],
            // "mostActiveLastMonth" => []
            ]
        );
    }
}
