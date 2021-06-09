<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\BlogPost;
use App\Models\User;

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

        // fetching all user and assign to use() function below
        $users = User::all();

        // no blog post to be found stop the code
        if($posts->count() === 0 || $users->count() === 0) {
            $this->command->info('There are no blog posts, so no comments will be added');
            return;
        }

        $commentsCount = (int)$this->command->ask('How many comments would you like?', 150);


        // create a blog post comment seed
        Comment::factory()->count($commentsCount)->make()->each(function ($comment) use($posts, $users) {
            // since comment table has polymorphic column of commentable_type and commentable_id from migration, so we need to restructure the seeder
            $comment->commentable_id = $posts->random()->id;
            $comment->commentable_type = BlogPost::class; // create a App\BlogPost;

            // since blog_post_id was remove  in migration 2021_05_14_111340_add_polymorhic
            // $comment->blog_post_id = $posts->random()->id; // commented out this
            $comment->user_id = $users->random()->id;
            $comment->save();
        });

          // create a user comment seed because change of polymorphic migration, watch episode 204
        Comment::factory()->count($commentsCount)->make()->each(function ($comment) use($posts, $users) {
            // since comment table has polymorphic column of commentable_type and commentable_id from migration, so we need to restructure the seeder
            $comment->commentable_id = $users->random()->id;
            $comment->commentable_type = User::class; // create a App\User;

            // since blog_post_id was remove in migration 2021_05_14_111340_add_polymorhic... to accept blog_post_id accept null in down() method
            $comment->user_id = $users->random()->id;
            $comment->save();
        });
    }
}
