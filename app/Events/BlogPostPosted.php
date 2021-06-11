<?php

namespace App\Events;

use App\Models\BlogPost;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// php artisan make:event BlogPostPosted
// every controller tter have different event

// this for PostsController.php
class BlogPostPosted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(BlogPost $blogPost)
    {
        $this->blogPost = $blogPost; // this will auto pass down to registered Event in EventServiceProvider like NotifyAdminWhenBlogPostCreated.php
    }
}
