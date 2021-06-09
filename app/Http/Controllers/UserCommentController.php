<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Models\User;
use Illuminate\Http\Request;

//php artisan make:controller UserCommentController
// just like PostsController.php, we create seperate controller to PostCommentController.php
// and look web.php only specify which controller method to use
class UserCommentController extends Controller
{
    public function __construct() {
        // mean user has to authenticated before can perform store action
        $this->middleware("auth")->only(["store"]);
    }

    // StoreComment request command "php artisan make:request StoreComment"
    // Do some validation in StoreComment rule function
    // want to see why got blogPost parameter, run "php artisan route:list", uri: posts/{post}/comments, name: posts.comments.store
    // since uri got {user} wild card so we should insert BlogPost in first argument
    public function store(User $user, StoreComment $request) {
        // Comment::create(and passing array of data or attribute)
        // instead can actually call post comment relation itself
        // got to User.php adding $fillable = [], bcuz it get from $user->commentsOn()
        // come from route's [$user=>$user->id] from users/show.blade.php, even it's a integer id, laravel will know what to pass into User.php commentOn's morphMany()
        $user->commentsOn()->create([
            // assign content, since we already use store request, it's already validate the data, if it invalid, it will return the previous page with error message which we can access inside the form
            "content" => $request->input("content"),
            // user id with already authenticated already come with StoreComment
            "user_id" => $request->user()->id
        ]);

         // can render in view layout.app about status
        // $request->session()->flash("status", "Comment was created"); // equivalent to withStatus();

        // redirect back to last page we on, like google chrome closed tab.
        return redirect()->back()
                ->withStatus("Comment was created");
    }
}
