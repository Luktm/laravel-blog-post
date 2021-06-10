<?php

namespace App\Observers;

use App\Models\BlogPost;
use Illuminate\Support\Facades\Cache;

// * php artisan make:observer BlogPostObserver --model=BlogPost
// * bind with BlogPost.php
// * it similar to static::creating, created, deleted at BlogPost.php boot() method
// * after that register at AppServiceProvider.php
// ? put "BlogPost::observe(BlogPostObserver::class)"  at AppServiceProvider.php
class BlogPostObserver
{
    public function deleting(BlogPost $blogPost)
    {
        // look back at the BlogPost.php boot() method, it similar to that
        // dd("i'm deleted'"); try to delete post
        $blogPost->comments()->delete();
        // enable to PostController.php at line 330 Storage::delete() method
        // $blogPost->image()->delete();

        // remove cache when blogpost was deleted
        Cache::tags(["blog-post"])->forget("blog-post-{$blogPost->id}");
    }

    public function restoring(BlogPost $blogPost)
    {
        $blogPost->comments()->restore();
    }

}
