<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Events\CommentPosted;
use App\Models\BlogPost;

// this is an api, it's better give alias suffix Resource
use App\Http\Resources\Comment as CommentResource;

// php artisan make:controller UserCommentController
// just like PostsController.php, we create seperate controller to PostCommentController.php
// and look web.php only specify which controller method to use
class PostCommentController extends Controller
{

    // constructor: when this PostCommentController was call, __construct will always run first
    public function __construct() {
        // mean user has to authenticated before can perform store action
        $this->middleware("auth")->only(["store"]);
    }

    // API docs https://laravel.com/docs/8.x/responses#view-responses
    public function index(BlogPost $post) {
        // dump(is_array($post->comments)); //false, not array, it's a collection, but laravel know how to convert to array mean json term in laravel
        // dump(get_class($post->comments)); // "Illuminate\Database\Eloquent\Collection"
        // die(); // go to postman preview tab
        // return $post->comments; // http://127.0.0.1:8000/posts/1/comments

        // CommentResource from Resource/comment.php will change a little bit, it has "data": {""} wrapper
        // this is userful for array collection

        // return new CommentResource($post->comments->first());
        // or return collection static method cannot insert new keyword
        // *::collection return array nested object { data: [ { "id": 9, "content": "lorem ipsum" } ] }
        // for api go api.php and Api/PostCommentController.php
        return CommentResource::collection($post->comments()->with("user")->get());
        // * to have user in json, we have to call $post->comments()->with("user")->get() in controller
        // * remember if wanted to call more method must use () like $post->comments to $post->comments()


        // if you have a query use comments()->with()->get(); always call get()
        return $post->comments()->with("user")->get(); // return user model relation http://127.0.0.1:8000/posts/1/comments
        // * hide some user information from json in User.php and Comment.php
    }

    // StoreComment request command "php artisan make:request StoreComment"
    // Do some validation in StoreComment rule function
    // want to see why got blogPost parameter, run "php artisan route:list", uri: posts/{post}/comments, name: posts.comments.store
    // since uri got {post} wild card so we should insert BlogPost in first argument
    public function store(BlogPost $post, StoreComment $request) {
        // Comment::create(and passing array of data or attribute)
        // instead can actually call post comment relation itself
        // got to Comments.php adding $fillable = [], bcuz it get from $post->comments(),
        $comment = $post->comments()->create([
            // assign content, since we already use store request, it's already validate the data, if it invalid, it will return the previous page with error message which we can access inside the form
            "content" => $request->input("content"),
            // user id with already authenticated already come with StoreComment
            "user_id" => $request->user()->id
        ]); // * it can actually return the created comment data which already saved

        // ? Mail delay Play around started here

        // * pass it to Mail send to CommentPosted.php
        // * send
        // Mail::to($post->user)->send(
        //     // new CommentPosted($comment)
        //     new CommentPostedMarkdown($comment) // episode 216, refer to commented-markdown.blade.php
        //     // after that go post a new comment Mailtrap website will receive a template from this page
        // );

        // * queue()
        // * alternatively line 37, put it on the queue() as , same as implements ShouldQueue in CommentPostedMarkdown.php
        // always open a "php artian queue:work" in terminal
        // so when user post a new comment terminal will do a job
        // Mail::to($post->user)->queue(
        //     // new CommentPosted($comment)
        //     new CommentPostedMarkdown($comment) // episode 216, refer to commented-markdown.blade.php
        //     // after that go post a new comment Mailtrap website will receive a template from this page
        // );

        // why this happend, find it from app/Event and app/Listener,
        // ? dispatch event from CommentPosted.php event, not mail
        // see php artisan command inside CommentPosted.php how to create event and listener
        event(new CommentPosted($comment)); // import from Event
        // ? dipatch on event something has happened and then handle that in the other listener NotifyUsersAboutComment.php place.

        // ? put it into event()
        // // * job always use ClassJob::dispatch, it equvilent to line 52 of Mail::to($post->user)->queue()
        // // this is keep retries to send the mail, throttle is controlling the flow
        // ThrottledMail::dispatch(new CommentPostedMarkdown($comment), $post->user)
        //     // high will executed first "php artisan queue:work --tries=3 --timeout=15 --queue=high,default,low", then post a comment
        //     // which mean high should run first
        //     ->onQueue("low");

        // // pass comment into construtor,
        // // * it located in app/Jobs folder, so should use dispatch() for every job class, remember run "php artisan queue:work --tries=3"
        // NotifyUsersPostWasCommented::dispatch($comment)
        //     // run "php artisan queue:work --tries=3 --timeout=15 --queue=high,default,low", which mean high should run first
        //     ->onQueue("high");
        // // * run "php artisan queue:work --tries=3" and post a new comment to see the process in terminal
        // // * click NotifyUsersPostWasCommented.php see handle() method

        // * later(), wait for 1 min to execute "php artisan queue:work"
        // $when = now()->addMinute(1);
        // Mail::to($post->user)->later(
        //     $when,
        //     new CommentPostedMarkdown($comment)
        // );

        // ? Play around ended here

         // can render in view layout.app about status
        $request->session()->flash("status", "Comment was created");

        // redirect back to last page we on, like google chrome closed tab.
        return redirect()->back();
    }
}
