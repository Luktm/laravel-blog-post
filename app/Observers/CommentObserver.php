<?php

namespace App\Observers;

use App\Models\Comment;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Cache;


// * php artisan make:observer CommentObserver --model=Comment
// * bind with Comment.php
// * it similar to static::creating, created, deleted at Comment.php boot() method
// but this way more cleaner
// * after that register at AppServiceProvider.php

class CommentObserver
{
    public function creating(Comment $comment) {
        // dd("i'm creating'"); try to create a comment
        if($comment->commentable_type == BlogPost::class) { // check the type from commentable_type while creating comment hasn't in database
            Cache::tags(["blog-post"])->forget("blog-post-{$comment->commentable_id}");
            // since ActivityComposer.php's view component store mostCommented cache tag at line 12, creating every comment, we shall reset the cache in case we add a lot comment
            Cache::tags(["blog-post"])->forget("mostCommented");
        }

    }
}
