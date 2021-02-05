<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\BlogPost;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = BlogPost::all();

        // no blog post to be found stop the code
        if($posts->count() === 0) {
            $this->command->info('There are no blog posts, so no comments will be added');
            return;
        }

        $commentsCount = (int)$this->command->ask('How many comments would you like?', 150);

        Comment::factory()->count($commentsCount)->make()->each(function ($comment) use($posts) {
            $comment->blog_post_id = $posts->random()->id;
            $comment->save();
        });
    }
}
