<?php

namespace App\Listeners;

use App\Events\CommentPosted;
use App\Jobs\NotifyUsersPostWasCommented;
use App\Jobs\ThrottledMail;
use App\Mail\CommentPostedMarkdown;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

// ? because Event always need listener or more listner, listener name convertion prefix "Notify"
// * php artisan make:event CommentPosted, it's located app/Event
// * php artisan make:listener NotifyUsersAboutComment, it's located app/Listener

class NotifyUsersAboutComment
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CommentPosted $event) // event come from CommentPosted public comment
    {
          // * job always use ClassJob::dispatch, it equvilent to line 52 of Mail::to($post->user)->queue()
        // this is keep retries to send the mail, throttle is controlling the flow
        ThrottledMail::dispatch(new CommentPostedMarkdown($event->comment), $event->comment->commentable->user) // Comment.php has commetable polymorphic relation has BlogPost.php and User.php
            // high will executed first "php artisan queue:work --tries=3 --timeout=15 --queue=high,default,low", then post a comment
            // which mean high should run first
            ->onQueue("low");

        // pass comment into construtor,
        // * it located in app/Jobs folder, so should use dispatch() for every job class, remember run "php artisan queue:work --tries=3"
        NotifyUsersPostWasCommented::dispatch($event->comment)
            // run "php artisan queue:work --tries=3 --timeout=15 --queue=high,default,low", which mean high should run first
            ->onQueue("high");
        // * run "php artisan queue:work --tries=3" and post a new comment to see the process in terminal
        // * click NotifyUsersPostWasCommented.php see handle() method

    }
}
// remember call event CommentPosted and NotifyUsersAboutComment.php listener at EventServiceProvider.php, parent event will pass constructor data automatically to nested listener
// run "php artisan queue:work --tries=3 --timeout=15 --queue=high,default,low"
// ? job must always put inside listener
