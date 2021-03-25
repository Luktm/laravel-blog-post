<?php

namespace App\Models;

use App\Scopes\LatestScope;
use App\Scopes\DeletedAdminScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    // protected $table = 'blogposts'

    // use SoftDeletes, it create a delete_at column at blogpost table in database
    use SoftDeletes;


    protected $fillable = ['title', 'content', 'user_id']; // column, go PostsController store() see BlogPost::create(validtated)

    use HasFactory;

    // hasMany() mean inside (Comment::class) / comment has table has foreign key
    public function comments() {
        // the latest can be added from here too, alternative is in PostController.php line 136
        return $this->hasMany(Comment::class)->latest();
    }

    // belongsTo() mean this or BlogPost table contain user foreign id column/field
    public function user() {
        return $this->belongsTo(User::class);
    }

    // local query scope only  watch episode 145
    // use in PostController.php at line 66 BlogPost::latest()->withCount('table')->get()
    public function scopeLatest(Builder $query) {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    // php artisan tinker and run
    // BlogsPost::mostCommented()->get()->pluck('comments_count');
    public function scopeMostCommented(Builder $query) {
        // with count producce comments_count column
        return $query->withCount('comments')->orderBy('comments_count', 'desc');
    }

    // model event at episode 126, solve on when trying to delete BlogPost from the table which contain comments foreign key

    public static function boot(){

        // please find this from Scopes folder in apply method use for global query orderBy()
        // this effect will reflect in PostController.php's BlogPost::withCount('column')
        // global search in vsc 'withTrashed()'
        static::addGlobalScope(new DeletedAdminScope);

        // boot place below addGlobalScope() to see withTrashed() method
        parent::boot();

        // consider it hard delete, mean completely delete from table
        // unless Comment.php added soft delete with migration add and run,
        // it will add deleted_at field instead
        static::deleting(function(BlogPost $blogPost) {
            $blogPost->comments()->delete();
        });

        // restore deleted blogpost contain comment
        // where it has relation with that particular comment or comments
        static::restoring(function(BlogPost $blogPost) {
            $blogPost->comments()->restore();
        });
    }
}
