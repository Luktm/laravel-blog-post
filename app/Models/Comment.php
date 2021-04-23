<?php

namespace App\Models;

use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

    // we did the same at BlogPost.php
    // refer this from comment able in mysql
    // and go back to PostCommentController.php at line 27
    protected $fillable = ["user_id", "content"];

    public function blogPost() {
        // return $this->belongsTo(BlogPost::class, 'post_id', 'blog_post_id');
        return $this->belongsTo(BlogPost::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

     // local query scope episode 145
    // use in PostController.php at line 133 Comment::latest()->withCount('table')->get()
    public function scopeLatest(Builder $query) {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public static function boot(){
        parent::boot();

        // please find this in LatestScope.php in apply method use for global query orderBy()
        // this effect will reflect in controller
        // static::addGlobalScope(new LatestScope);

         // when the comment creating
        static::creating(function (Comment $comment) {
            //  Cache::tags(["blog-post"]) initially set in PostController.php at line 167
            // and it will immediately see this comment otherwise it will be cache in PostController.php at line 167
            Cache::tags(["blog-post"])->forget("blog-post-{$comment->blog_post_id}");
            // since ActivityComposer.php's view component store mostCommented cache tag at line 12, creating every comment, we shall reset the cache in case we add a lot comment
            Cache::tags(["blog-post"])->forget("mostCommented");

        });

    }
}
