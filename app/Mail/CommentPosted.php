<?php

namespace App\Mail;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

// ? php artisan make:mail CommentPosted
// laravel will auto seperate camel case CommentPost to Comment Post
class CommentPosted extends Mailable
{
    use Queueable, SerializesModels;

    public $comment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment; // assign comment to this->comment
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Commented was posted on your blog post {$this->comment->commentable->title} blog post";
        // from() checked from whom email
        // return $this->subject($subject)->from("admin@laravel.test")->view('emails.posts.commented');
        // dd($this->comment->user->image->path);
        // dd(public_path());
        return $this

            // First example with full path
            // ->attach(
            //     storage_path("app/public") . "/" . $this->comment->user->image->path,  // use this storage_path() to get local storage path
            //     [
            //         "as" => "profile_image", // rename attachment image
            //         // "mime" => "image/png"
            //     ]
            // ) // attach file use this method
            // ->attachFromStorage($this->comment->user->image->path, "profile_picture.jpeg") // second parament is rename
            // ->attachFromStorageDisk("public", $this->comment->user->image->path) // second parament is rename
            ->attachData(Storage::get($this->comment->user->image->path), "profile_picture_from_data.png", [
                "mime" => "image/png",
            ])
            ->subject($subject)
            ->view('emails.posts.commented');
            
            // this will send to the MailTrap where the credential been added in .env
    }
}
