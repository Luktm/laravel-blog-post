<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * php artisan make:test ApiPostCommentsTest
 * docs https://laravel.com/docs/8.x/http-tests#assert-json
 */
class ApiPostCommentsTest extends TestCase
{
    use RefreshDatabase; // must include this
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_new_blog_post_does_not_have_comments()
    {
        // create blogpost with user, factory come with BlogPostFactory for db seeder
        //* move it to the TestCase.php if it a repeating pattern
        // BlogPost::factory()->create([
        //     "user_id" => $this->user()->id
        // ]);

        // check it in TestCase.php for global function
        $this->blog_post();

        /**
         * wonder the path has api/v1 prefix see it from api.php, RouteServiceProvider.php,
         * run ./vendor/bin/phpunit
         */
        $response = $this->json("GET", "api/v1/posts/1/comments");

        $response->assertStatus(200)
        // bcuz it will fetch a lot of properties, to save time use JsonStructure see root json exist or not
        ->assertJsonStructure(["data", "links", "meta"])
        // check data: properties actual size
        ->assertJsonCount(0, "data");
    }

    /**
     * run ./vendor/bin/phpunit
     */
    public function test_blog_post_has_10_comments() {
        /**
         * create blog post model factory
         * create blog post need user id
         * generate 10 comments for each blogpost
         * created comments also need user_id
         * * move it to the TestCase.php if it a repeating pattern
         */
        // BlogPost::factory()->create([
        //     "user_id" => $this->user()->id
        // ])
        $this->blog_post()->each(function (BlogPost $post){
            $post->comments()->saveMany(
                Comment::factory()->count(10)->make([
                    "user_id" => $this->user()->id
                ])
            );
        });

        // {post} id must increase one for every test
        $response = $this->json("GET", "api/v1/posts/2/comments");

        $response->assertStatus(200)
        // bcuz it will fetch a lot of properties, to save time use JsonStructure see root json exist or not
        ->assertJsonStructure([
            "data" => [
                // asterisk mean whatever parent properties should contain these nested properties
                "*" => [
                    "id",
                    "content",
                    "created_at",
                    "updated_at",
                    "user" => [
                        "id",
                        "name",
                    ]
                ]
            ],
            "links",
            "meta"
        ])
        // check data: properties actual size
        ->assertJsonCount(10, "data");
    }

    public function test_adding_comments_when_not_authencticated() {
        // * move it to the TestCase.php if it's a repeating pattern
        $this->blog_post();

        /**
         * run php artisan route:list to know what url available
         * add new comment with not authenticated
         */
        $response = $this->json("POST", "api/v1/posts/3/comments", [
            "content" => "Hello"
        ]);

        $response->assertStatus(401); // not authenticated is 401 status code or assertUnauthrized
    }

    public function test_adding_comments_when_authencticated() {
        // * move it to the TestCase.php if it's a repeating pattern
        $this->blog_post();

        /**
         * run php artisan route:list to know what url available
         * add new comment with not authenticated
         * actingAs mean place a user to authenticating to add new comment, specify it's a api in auth.php guards=>["api"]
         * remember always increment post id for every test run
         */
        $response = $this->actingAs($this->user(), "api")->json("POST", "api/v1/posts/4/comments", [
            "content" => "Hello"
        ]);

        $response->assertStatus(201); // created is 201 status code or assertUnauthrized
    }

    public function test_adding_comment_with_invalid_data() {
        // * move it to the TestCase.php if it's a repeating pattern
        $this->blog_post();

        /**
         * run php artisan route:list to know what url available
         * add new comment with not authenticated
         * actingAs mean place a user to authenticating to add new comment, specify it's a api in auth.php guards=>["api"]
         * remember always increment post id for every test run
         */
        $response = $this->actingAs($this->user(), "api")->json("POST", "api/v1/posts/5/comments", []);

        // also can test it in postmon, remember put bearer key, Accept is application/json but pass {} empty body
        $response->assertStatus(422)->assertJson([
            "message" => "The given data was invalid",
            "errors" => [
                "content" => [
                    "The content field is required"
                ]
            ]
        ]); // invalid data is 422 status code
    }
}
