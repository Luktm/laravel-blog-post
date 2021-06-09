<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // one to many relation with blogPost, in future project please change it to blogPost()
    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function commentsOn() {
        return $this->morphMany(Comment::class, "commentable")->latest();
    }

    // episode 196, since created new AddPolymorphToImagesTable migration, blog_post table and user table use the same image table by running "php artisan make:migration AddPolymorphToImagesTable"
    // which contain imageable_id and imageable_type, so hasOne() got to change to morphOne();
    public function image() {
        // return $this->hasOne(Image::class);
        return $this->morphOne(Image::class, "imageable"); // it's know how to store data into imageable_id and imageable_type in image table. Front name can be differ such as abc_id, abc_type but should follow what column has after run migrate
    }


    // get the most BlogPost by local query, remember scope prefix always omitted to withMostBlogPost
    public function scopeWithMostBlogPosts(Builder $query)
    {
        // withCount will return xxx_xxx_count name, blogPosts equivalent to line 46
        return $query->withCount('blogPosts')->orderBy('blog_posts_count', 'desc');
    }

    public function scopeWithMostBlogPostsLastMonth(Builder $query)
    {
        // withCount will return xxx_xxx_count name, blogPosts equivalent to line 46
        // also can do specific query
        return $query->withCount(['blogPosts' => function (Builder $query) {
            // episode 148 operator for static class, it is access method fields of filter blogPost
            // helper function called from to, now() mean current time
            $query->whereBetween(static::CREATED_AT, [now()->subMonths(1), now()]);
        }])
            // sqlite doesn't like having('blog_posts_count', '>=', 2), we use has() instead
            ->has('blogPosts', '>=', 2)
            ->orderBy('blog_posts_count', 'desc');
    }

    // scope prefix name remove if we going to run it, User::thatHasCommentedOnPost($post)->get() and see line 96.
    // * this was use in the NotifyUsersPostWasCommented.php.
    public function scopeThatHasCommentedOnPost(Builder $query, BlogPost $post) { // post has in relationship
         // has comments relationship at line 51 comments()
        return $query->whereHas("comments", function($query) use($post) { // we working on comments relationship, each comment has commentable_id field
            return $query->where("commentable_id", "=", $post->id) // check commentable_id is equal to current post id
                    ->where("commentable_type", "=", BlogPost::class); // check if commentable_type is App\BlogPost
        });
    }
    // after that run php artisan tinker
    // $post = BlogPost::find(1);
    // User::thatHasCommentedOnPost($post)->get();
    // we can use that inside our job, go to NotifyUsersPostWasCommented.php in handle() method
}
