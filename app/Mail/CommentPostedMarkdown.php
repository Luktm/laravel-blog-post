<?php

namespace App\Mail;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
// ? This is automatically create when run the command in line 12, implement it in the class
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

// ? First
// php artisan make:mail CommentPostedMarkdown --markdown=emails.posts.commented-markdown
// create file in emails/posts then commented-markdown file

// ? Second
// * Episode 220, open "queue.php" to see sync change to database,
// * run "php artisan queue:table" and "php artisan migrate"
// * find the jobs table in database to see the queue

// * Implement ShouldQueue, laravel is smart, it figure out the mail as a background job when implement ShouldQueue, remember look back to .env QUEUE_CONNECTION=database
// * Once submit post comment it store in the database table call jobs
// * Open terminal run "php artisan" to see the job available queue command such as "php artisan queue:work"
// * After run it, it will remove from jobs table

// ? Third
// * Episode 222 - Dealing with failed jobs table.
// * run "php artisan queue:failed-table" and "php artisan migrate", then check with "php artisan queue:failed", and
// * called "failed_jobs" table will store the job failed all along.
// * run "php artisan queue:work --tries=3" this will attempt to proccess the job 3 times,
// * if failed it would ignore and run the next job.

// ? Fourth
// * make something would break at line 68, the job will move to the failed_job table
// * run "php artisan queue:failed" see failed_job table list
// * run "php artisan queue:retry id" id from line 61,
// * run "php artisan queue:failed" to see failed_job table list again
// * it should have attempted 3 times, so we shouldn't complain it as it's not laravel error

// * commented out $post = new BlogPost(); at line 77.
// * run "php artisan queue:restart" in new terminal, this is not going to restart the "php artisan queue:work",
// * nothing will restart, it's only restart processor
// * run again "php artisan queue:work --tries=3" because it's failed then job then stop automatically
// * in new terminal run "php artisan queue:failed" to see the failed_job list again
// * run "php artisan queue:retry id", then you will see the queue is working,
// * and it push back failed to onto queue, and proccessed success in the terminal,
// * where terminal "php artisan queue:work --tries=3" is ran.

// ? Summary queue work and failed_job
// * "php artisan queue:work --tries=3" in terminal A
// * "php artisan queue:failed" in terminal B
// * "php artisan queue:retry id" in terminal B
// * see the result in terminal A
// * "php artisan" see the available queue command.

// class CommentPostedMarkdown extends Mailable implements ShouldQueue

class CommentPostedMarkdown extends Mailable
{
    use Queueable, SerializesModels;

    public $comment;

    /**
     * Create a new message instance.
     *
     * @return void
     */

     // from PostCommentController.php
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // * make something would break, the job will move to the failed_job table
        // * run "php artisan queue:failed" see failed job list
        // * run "php artisan queue:retry id" id from line 61 to see
        // $post = new BlogPost();

        $subject = "Commented was posted on your blog post {$this->comment->commentable->title} blog post";
        return $this->subject($subject)
            ->markdown('emails.posts.commented-markdown'); // at resources/views/emails/posts/commented-markdown.blade.php
            // this will also send to MailTrap.com registered account with commented-markdown.blade.php template
    }
}
