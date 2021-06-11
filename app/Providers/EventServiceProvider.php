<?php

namespace App\Providers;

use App\Events\BlogPostPosted;
use App\Events\CommentPosted;
use App\Listeners\CacheSubscriber;
use App\Listeners\NotifyAdminWhenBlogPostCreated;
use App\Listeners\NotifyUsersAboutComment;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // luk: one listener(Registered) handle can be handle by many listener
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        // luk: remember parent always an event class-> listener class
        CommentPosted::class => [
            // CommentPosted will automatically pass props to NotfiyUsersPostWasCommented handler() method
            NotifyUsersAboutComment::class,
        ],
        BlogPostPosted::class => [
            // BlogPostPosted constructor will pass props to NotifyAdminWhenBlogPostCreated handle()
            NotifyAdminWhenBlogPostCreated::class,
        ]
    ];

    // * php artisan make:listener CacheSubscriber
    // * for logging.php and storage/logs purpose
    // * call this class to run method here
    // * subscribe listener no need assign anything
    protected $subscribe = [
        CacheSubscriber::class
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
