<?php

namespace App\Jobs;

use App\Mail\CommentPostedOnPostWatched;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

// * php artisan make:job NotifyUsersPostWasCommented
// Episode 223 it create Jobs/NotifyUsersPostWasCommented.php
// Send email to notify user there is new comment
// * run "php artisan queue:failed" see failed_job table list if don't have one
// * run "php artisan queue:work --tries=3" to send again the failed_job
class NotifyUsersPostWasCommented implements ShouldQueue // * ShouldQueue is store it to jobs table in database
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $comment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Comment $comment) // pass some data here
    {
        $this->comment = $comment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // pass to User.php scopeThatHasCommentedOnPost(),
        // * return current user where has comment on Post, like flutter model getter method.
        // => Illuminate\Database\Eloquent\Collection {#4397
        //     all: [
        //       App\Models\User {#4356
        //         id: 7,
        //         name: "Cleve Strosin",
        //         email: "dickens.mike@example.net",
        //         email_verified_at: "2021-06-01 06:05:46",
        //         created_at: "2021-06-01 06:05:46",
        //         updated_at: "2021-06-01 06:05:46",
        //         is_admin: 0,
        //       },
        //     ],
        //   }

        // * thatHasCommentedOnPost return above at line 44
        User::thatHasCommentedOnPost($this->comment->commentable) // commentable has commentable_type(BlogPost::class) and commetable_id
            ->get()
            ->filter(function(User $user) { // this User just like flutter get in model it would know which user, bcuz of relationship
                return $user->id !== $this->comment->user_id; // filter out only the other user comment show out
            })->map(function(User $user) { // loop and do certain function
                // ThrottledMail will keep retries to send the mail
                ThrottledMail::dispatch(
                    new CommentPostedOnPostWatched($this->comment, $user),
                    $user
                ); // ThrottleMail equivalent to line started 70
                // Mail::to($user)->send() replace with later() send after 5 seconds
                // * remember run "php artisan queue:work --tries=3"
                // Mail::to($user)->later(
                //     $now->addSeconds(6),
                //     new CommentPostedOnPostWatched($this->comment, $user)
                // );// send use because CommentPostedOnPostWatched.php implements ShouldQueue
            });
        // this send all the mail to user except self
    }
}
