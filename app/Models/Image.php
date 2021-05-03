<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ["path", "blog_post_id"];

    public function blogPost() {
        return $this->belongsTo(BlogPost::class);
    }

    public function url() {
        // alternative beside just put this in show.blade.php, we can put it in here and use it.
        return Storage::url($this->path);
    }
}
