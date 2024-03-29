<?php

namespace App\Mail;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

// * php artisan make:mail CommentPostedOnPostWatched --markdown=emails.posts.comment-posted-on-watched
// * template create at ./resources/views/emails/posts/emails.posts.comment-posted-on-watched.blade.php
class CommentPostedOnPostWatched extends Mailable implements ShouldQueue // * save to jobs table in database, and run "php artisan queue:work --tries=3"
{
    use Queueable, SerializesModels;

    public $comment, $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Comment $comment, User $user)
    {
        $this->comment = $comment;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.posts.comment-posted-on-watched');
    }
}
