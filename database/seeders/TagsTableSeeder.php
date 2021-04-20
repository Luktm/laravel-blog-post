<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // collect() turn array to collection, so it can iterate
        $tags = collect(["Science", "Sport", "Politics", "Entertainment", "Economy"]);

        $tags->each(function ($tagName){
            $tag = new Tag();
            $tag->name = $tagName;
            $tag->save();
        });
        // after that go to DatabaseSeeder.php put it in call method
    }
}
