<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    // protected $table = 'blogposts'


    use SoftDeletes;


    protected $fillable = ['title', 'content']; // column, go PostsController store() see BlogPost::create(validtated)

    use HasFactory;

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    // model event at episode 126, solve on when trying to delete BlogPost from the table which contain comments foreign key

    public static function boot(){
        parent::boot();

        // consider it hard delete, mean completely delete from table
        // unless Comment.php added soft delete with migration
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
