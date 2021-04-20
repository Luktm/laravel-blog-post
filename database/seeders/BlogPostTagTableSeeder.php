<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class BlogPostTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // assign count in tag database
        $tagCount = Tag::all()->count();

        // detect tagCount was not created before this will get cancel
        if(0 === $tagCount) {
            $this->command->info("No tags found, skipping assigning tags to blog posts");
            return;
        }

        // convert to integer
        $howManyMin = (int)$this->command->ask("Minimum tags on blog post?", 0);
        // min will take the lower value of two argument
        $howManyMax = min((int)$this->command->ask("Maximum tags on blog post?", $tagCount), $tagCount);

        // get all blogpost and do something from above code statement
        BlogPost::all()->each(function(BlogPost $post) use($howManyMin, $howManyMax){
            // generate random integer which accept min and max and return number between of it
            $take = random_int($howManyMin, $howManyMax);
            // inRandomOrder return random order tag and take certain amount of tags and display id table only
            $tags = Tag::inRandomOrder()->take($take)->get()->pluck("id");
            $post->tags()->sync($tags);
        });
        // after that call it in DatabaseSeeder.php
    }
}
