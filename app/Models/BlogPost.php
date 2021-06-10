<?php

namespace App\Models;

use App\Scopes\LatestScope;
use App\Scopes\DeletedAdminScope;
use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class BlogPost extends Model
{
    // protected $table = 'blogposts'

    // use SoftDeletes, it create a delete_at column at blogpost table in database
    use SoftDeletes, Taggable; // Taggable look at the line 42 equivalent, it will still work as usual


    protected $fillable = ['title', 'content', 'user_id']; // column, go PostsController store() see BlogPost::create(validtated)

    use HasFactory;

    // hasMany() mean inside (Comment::class) / comment has table has foreign key
    // ? remember see at the line 19 Taggable's tags() method to see the Many to Many polymorphic relationship
    public function comments()
    {
        // the latest can be added from here too, alternative is in PostController.php line 136
        // return $this->hasMany(Comment::class)->latest();

        // since blog_post table has polymorhpic relation to comment table of commentable_id & commentable_type, use morhMany
        return $this->morphMany(Comment::class, "commentable")->latest(); // auto assign to commentable_id and commentable_type
    }

    // belongsTo() mean this or BlogPost table contain user foreign id column/field
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ! use taggable model trait in app/Traits/Taggable episode 209
    // // this imply will hold multiple model, many to many relation both model have to use "belongsToMany()"
    // public function tags()
    // {
    //     // withTimestamps() call, the time declaration will create whenever relation created in database, since CreateBlogPostTable has $table->timestamps();
    //     // return $this->belongsToMany(Tag::class)->withTimestamps();

    //     // taggable from 2021_05_31_rename..migration of the taggables table with the column call taggable_type & taggable_id the morph column prefix name
    //     // line 45 commented out because of changed relations from OneToMany to ManyToMany polymorphic
    //     return $this->morphToMany(Tag::class, "taggable")->withTimestamps();


    //     // run "php artisan tinker", all of this was attach many to many relation on episode 169
    //     // >>> $tag1 = new Tag();
    //     // => App\Models\Tag {#4326}
    //     // >>> $tag1->name = "Science";
    //     // => "Science"
    //     // >>> $tag2 = new App\Models\Tag;
    //     // => App\Models\Tag {#4321}
    //     // >>> $tag2->name = "Politics";
    //     // => "Politics"
    //     // >>> $tag1->save();
    //     // => true
    //     // >>> $tag2->save();
    //     // => true
    //     // >>> $blogPost = BlogPost::find(1);
    //     // // attach tag id to blog_post_tag->tag_id without unique relation id
    //     // >>> $blogPost->tags()->attach($tag1);
    //     // => null
    //     // >>> >>> $blogPost->tags()->attach([$tag1->id, $tag2->id]);
    //     // => null
    //     // >>> $tag3 = new App\Models\Tag;
    //     // => App\Models\Tag {#4338}
    //     // >>> $tag3->name = "Sport";
    //     // => "Sport"
    //     // >>> $tag3->save();
    //     // => true
    //     // // attach with unique id to blog_post_tag->tag-id
    //     // >>> $blogPost->tags()->syncWithoutDetaching([$tag1->id, $tag2->id, $tag3->id]);
    //     // => [
    //     //      "attached" => [
    //     //        5,
    //     //      ],
    //     //      "detached" => [],
    //     //      "updated" => [],
    //     //    ]
    //     // // sync was like true = !true vice verse attach or detach
    //     // >>> $blogPost->tags()->sync([$tag1->id, $tag2->id]);
    //     // => [
    //     //      "attached" => [],
    //     //      "detached" => [
    //     //        3 => 5,
    //     //      ],
    //     //      "updated" => [],
    //     //    ]

    // }

    // episode 196, since created new AddPolymorphToImagesTable migration, blog_post table and user table use the same image table by running "php artisan make:migration AddPolymorphToImagesTable"
    // which contain imageable_id and imageable_type, so hasOne() got to change to morphOne();
    public function image() {
        // return $this->hasOne(Image::class);
        return $this->morphOne(Image::class, "imageable"); // it's know how to store data into imageable_id and imageable_type in image table. Front name can be differ such as abc_id, abc_type but should follow what column has after run migrate
    }

    // local query scope only  watch episode 145
    // use in PostController.php at line 66 BlogPost::latest()->withCount('table')->get()
    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    // php artisan tinker and run
    // BlogsPost::mostCommented()->get()->pluck('comments_count');
    // remember if use in other place, scope should be remove therefore it become mostCommented()
    public function scopeMostCommented(Builder $query)
    {
        // with count producce comments_count column
        return $query->withCount('comments')->orderBy('comments_count', 'desc');
    }

    public function scopeLatestWithRelations(Builder $query) {
        return $query->latest()
            ->withCount('comments')
            ->with("user")
            ->with("tags");
    }

    // model event at episode 126, solve on when trying to delete BlogPost from the table which contain comments foreign key

    // ? alternative way is look at the folder app/Observer/BlogPostObserver.php
    // * php artisan make:observer BlogPostObserver --model=BlogPost
    public static function boot()
    {

        // please find this from Scopes folder in apply method use for global query orderBy()
        // this effect will reflect in PostController.php's BlogPost::withCount('column')
        // global search in vsc 'withTrashed()'
        static::addGlobalScope(new DeletedAdminScope);

        // boot place below addGlobalScope() to see withTrashed() method
        parent::boot();

        // ! Since we have BlogPostObserver.php so we commented out code below
        // // consider it hard delete, mean completely delete from table
        // // unless Comment.php added soft delete with migration added and run,
        // // it will add deleted_at field instead
        // static::deleting(function (BlogPost $blogPost) {
        //     $blogPost->comments()->delete();

        //     // enable to PostController.php at line 330 Storage::delete() method
        //     // $blogPost->image()->delete();

        //     // remove cache when blogpost was deleted
        //     Cache::tags(["blog-post"])->forget("blog-post-{$blogPost->id}");
        // });

        // // when the post updating, we can reset the cache from this particular item, so user press edit post, it will reset the cache to avoid conflict
        // static::updating(function (BlogPost $blogPost) {
        //     // it get the actual cache key name, such as Cache::remember("blog-post-{$id}") in PostController.php
        //     // but here we can read from $blogPost->id
        //     // in PostController.php because we have declare Cache::tags(["blog-post]) at line 167, before calling forget() the cache, we could possibly call the specify tags again then reset it.
        //     Cache::tags(["blog-post"])->forget("blog-post-{$blogPost->id}");
        // });

        // // restore deleted blogpost contain comment
        // // where it has relation with that particular comment or comments
        // static::restoring(function (BlogPost $blogPost) {
        //     $blogPost->comments()->restore();
        // });
    }
}
