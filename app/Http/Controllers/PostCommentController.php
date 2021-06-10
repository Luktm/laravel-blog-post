<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Jobs\NotifyUsersPostWasCommented;
use App\Jobs\ThrottledMail;
use App\Mail\CommentPosted;
use App\Mail\CommentPostedMarkdown;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

        // * job always use ClassJob::dispatch, it equvilent to line 52 of Mail::to($post->user)->queue()
        // this is keep retries to send the mail, throttle is controlling the flow
        ThrottledMail::dispatch(new CommentPostedMarkdown($comment), $post->user)
            // high will executed first "php artisan queue:work --tries=3 --timeout=15 --queue=high,default,low", then post a comment
            // which mean high should run first
            ->onQueue("low");

        // pass comment into construtor,
        // * it located in app/Jobs folder, so should use dispatch() for every job class, remember run "php artisan queue:work --tries=3"
        NotifyUsersPostWasCommented::dispatch($comment)
            // run "php artisan queue:work --tries=3 --timeout=15 --queue=high,default,low", which mean high should run first
            ->onQueue("high");
        // * run "php artisan queue:work --tries=3" and post a new comment to see the process in terminal
        // * click NotifyUsersPostWasCommented.php see handle() method

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
