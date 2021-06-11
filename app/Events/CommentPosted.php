<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// ? because Event always need Listener or more Listner, listener name convertion prefix "Notify"
// * php artisan make:event CommentPosted, it's located app/Event
// * php artisan make:listener NotifyUsersAboutComment, it's located app/Listener

// * remember register at EventServiceProvider.php at listener method
class CommentPosted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }
}
// remember call NotifyUsersAboutComment.php listener at EventServiceProvider.php
// run "php artisan queue:work --tries=3 --timeout=15 --queue=high,default,low"
