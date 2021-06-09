<?php

namespace App\Traits;

use App\Models\Tag;

// ? This has been use and has relationship in BlogPost.php, for extension like swift.
trait Taggable {

    // watch episode 208
    // protected is like public, but it is only accessible from class inherit from it.
    protected static function bootTaggable() { // same as boot() method in every model

        // this will execute before insert the data to database
        static::updating(function($model) { // model is BlogPost.php of blog_post table. Column got [title, content, user_id]
            $model->tags()->sync(static::findTagsInContent($model->content)); // many to many relation use sync method, it will replace all relationship that this model actually had with new model relationship
        });

         // this will execute before insert the data to database
        static::created(function($model) { // we need actually id from the model
            $model->tags()->sync(static::findTagsInContent($model->content)); // many to many relation use sync method, it will replace all relationship that this model actually had with new model relationship
        });

    }

    // this imply will hold multiple model, many to many relation both model have to use "belongsToMany()"
    // just a migration from Tag.php from tags() method
    public function tags()
    {
        // withTimestamps() call, the time declaration will create whenever relation created in database, since CreateBlogPostTable has $table->timestamps();
        // return $this->belongsToMany(Tag::class)->withTimestamps();

        // taggable from 2021_05_31_rename..migration of the taggables table with the column call taggable_type & taggable_id the morph column prefix name
        // line 45 commented out because of changed relations from OneToMany to ManyToMany polymorphic
        return $this->morphToMany(Tag::class, "taggable")->withTimestamps();

    }

    // $content come from line 16 and 21
    private static function findTagsInContent($content) {
        // it will check those tag whether is inside the database, if yes it become the tag automatically
        preg_match_all("/@([^@]+)@/m", $content, $tags); // regex accept @ front and @behind and middle accept character except @, + is whole character. Eg: @Sceience@

        // visit https://regexr.com/ and see group Detail
        return Tag::whereIn("name", $tags[1] ?? [])->get(); // find in tags table of name column of index 1 which is Science, Sport, Politic, Entertainment, and economy from the regex
    }
    // then go create a new blog post, in the content insert "@Science@ @Sport@" it will check those tag whether is inside the database, if yes it become the tag automatically
}
