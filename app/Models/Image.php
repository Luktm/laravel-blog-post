<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    // protected $fillable = ["path", "blog_post_id"];
    protected $fillable = ["path"]; // remove blog_post_id column

    // previously is blogPost(), now change to imageable() from episode 196
    public function imageable() { // remember the migration field name is imagaeble
        // return $this->belongsTo(BlogPost::class);
        return $this->morphTo(); // change to morphTo due to changed from OneToOne relation to OneToOne Polymorph
    }

    public function url() {
        // alternative beside just put this in show.blade.php, we can put it in here and use it.
        return Storage::url($this->path);
    }
}
