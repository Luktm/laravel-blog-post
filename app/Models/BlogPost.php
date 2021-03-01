<?php

namespace App\Models;

use App\Scopes\LatestScope;
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
        return $this->hasMany(Comment::class);
    }

    // belongsTo() mean this or BlogPost table contain user foreign id column/field
    public function user() {
        return $this->belongsTo(User::class);
    }

    // model event at episode 126, solve on when trying to delete BlogPost from the table which contain comments foreign key

    public static function boot(){
        parent::boot();

        // please find this in LatestScope.php in apply method use for global query orderBy()
        // this effect will reflect in PostController.php's BlogPost::withCount('column')
        static::addGlobalScope(new LatestScope);

        // consider it hard delete, mean completely delete from table
        // unless Comment.php added soft delete with migration,
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
