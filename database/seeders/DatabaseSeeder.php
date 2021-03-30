<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // ! this is fix for running command 'php artisan migrate:refresh --seed'
        // \App\Models\User::factory(10)->create();

        // DB::table('users')->insert([
        //     'name' => 'John Doe',
        //     'email' => 'john@laravel.test',
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        // ]);

        // refer to UserFactory.php new_user() method, this will actually equavalent to the above
        // $doe = User::factory()->new_user()->create();

        // $else = User::factory()->count(20)->create();

        // // keep 21 user inside
        // $users = $else->concat([$doe]);

        // must be right in order else error when fetch empty data to another class

        // set by default to true
        if ($this->command->confirm('Do you want to refresh the database?')) {

            $this->command->call('migrate:refresh');
            $this->command->info('Database was refreshed');
            // then run 'php artisan db:seed'

        }

        Cache::tags(['blog-post'])->flush();

        $this->call([
            UsersTableSeeder::class,
            BlogPostsTableSeeder::class,
            CommentsTableSeeder::class,
        ]);

        // fix blogpost got user_id
        // make will make a collection

        // $posts = BlogPost::factory()->count(50)->make()->each(function($post) use($users){
        //     // set blogpost table's user_id with random id
        //     $post->user_id = $users->random()->id;
        //     $post->save();
        // });

        // fix blogpost got blog_post_id
        // $comment = Comment::factory()->count(150)->make()->each(function ($comment) use($posts) {
        //     $comment->blog_post_id = $posts->random()->id;
        //     $comment->save();
        // });
    }
}
