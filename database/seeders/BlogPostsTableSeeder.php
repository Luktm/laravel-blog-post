<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\User;

class BlogPostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $blogCount = (int)$this->command->ask('How many blog posts would you like?', 20);
        // we are fetching the users
        $users = User::all();

        BlogPost::factory()->count($blogCount)->make()->each(function($post) use($users){
            // set blogpost table's user_id with random id
            $post->user_id = $users->random()->id;
            $post->save();
        });
    }
}
