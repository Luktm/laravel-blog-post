<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{

    // constructor: when this PostCommentController was call, __construct will always run first
    public function __construct() {
        // mean user has to authenticated before can perform store action
        $this->middleware("auth")->only(["store"]);
    }

    // StoreComment request command "php artisan make:request StoreComment"
    // Do some validation in StoreComment rule function
    // want to see why got blogPost parameter, run "php artisan route:list", uri: posts/{post}/comments, name: posts.comments.store
    // since uri got {post} wild card so we should insert BlogPost in first argument
    public function store(BlogPost $post, StoreComment $request) {
        // Comment::create(and passing array of data or attribute)
        // instead can actually call post comment relation itself
        // got to Comments.php adding $fillable = [], bcuz it get from $post->comments()
        $post->comments()->create([
            // assign content, since we already use store request, it's already validate the data, if it invalid, it will return the previous page with error message which we can access inside the form
            "content" => $request->input("content"),
            // user id with already authenticated already come with StoreComment
            "user_id" => $request->user()->id
        ]);

         // can render in view layout.app about status
        $request->session()->flash("status", "Comment was created");

        // redirect back to last page we on, like google chrome closed tab.
        return redirect()->back();
    }
}
