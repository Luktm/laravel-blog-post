<?php

namespace App\Listeners;

use App\Events\BlogPostPosted;
use App\Jobs\ThrottledMail;
use App\Mail\BlogPostAdded;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

// php artisan make:listener NotifyAdminWhenBlogPostCreated
// remember register with EventServiceProvider.php assign it to BlogPostPosted.php event
// php artisan make:mail BlogPostAdded --markdown=emails.posts.blog-post-added,
// it create mail and template at the same time
class NotifyAdminWhenBlogPostCreated
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(BlogPostPosted $event) // pass by Event class
    {
        // from User.php scopethatIsAnAdmin will return is_admin list
        User::thatIsAnAdmin()->get()
            // and send all the mail to admin
            ->map(function(User $user) {
                ThrottledMail::dispatch(
                    new BlogPostAdded(),
                    $user // send email to the user
                );
            });
    }
}
