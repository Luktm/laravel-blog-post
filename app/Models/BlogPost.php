<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = ['title', 'content']; // column, go PostsController store() see BlogPost::create(validtated)

    use HasFactory;

    public function comments() {
        return $this->hasMany(Comment::class);
    }
}