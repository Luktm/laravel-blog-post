<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 *  * php artisan make:test PostTest
 */
class PostTest extends TestCase
{
    // ? REMEMBER EVEN I CHANGE ANYTHING IN phpunit.xml or database.php, don't worry too much, RefreshDatabse will take care of that
    use RefreshDatabase; // Recreate database structure by running all the migration on each test run from sqlite, refer database.php & phpunit.xml

    public function test_no_blog_post_when_nothing_in_database()
    {
        $response = $this->get('/posts');

        $response->assertSeeText('No posts found!');
    }

    public function test_see_1_blog_post_when_there_is_1_with_no_comments() {
        // Arrange
        $post = $this->create_dummy_blog_post();

        // Act
        $response = $this->get('/posts');

        // Assert
        $response->assertSeeText('New title');
        $response->assertSeeText('No comments yet');
        $this->assertDatabaseHas('blog_posts', [
            'title'=> 'New title'
        ]);
    }

    public function test_see_1_blog_post_with_comments() {

        // Arrange
        $user = $this->user();

        $post = $this->create_dummy_blog_post();

        // Generate 4 comments
        // Comment::factory()->count(4)->create([
        //     'blog_posts_id' => $post->id
        // ]);

        // bcuz of polymorphic
        Comment::factory()->count(4)->create([
            'commeteable_id' => $post->id,
            "commentable_type" => BlogPost::class,
            "user_id" => $user->id,
        ]);

        $response = $this->get('/posts');

        $response->assertSeeText('4 comments');
    }

    public function test_store_valid() {
        $params = [
            'title' => 'Valid title',
            'content' => 'At least 10 characters'
        ]; // params get from url

        // actingAs laravel will treat it as authenticated
        $this->actingAs($this->user)
            // Post request, run php artisan route:list, it's a post.store
            ->post('/posts', $params)
            ->assertStatus(302) // redirect status code is 302
            ->assertSessionHas('status'); // go PostsController.php find flash('status', 'fjdlsjalfs')

            // read the status return
            $this->assertEquals(session('status'), 'The blog post was created');
    }

    public function test_store_fail() {
        $params = [
            'title' =>'x',
            'context' => 'x',
        ];

        // Post request
        $this->post("/posts", $params)
            ->assertStatus(302) // redirect status code is 302
            ->assertSessionHas('errors'); // go PostsController.php error flash is added globally

        $messages = session('errors')->getMessages();

        $this->assertEquals($messages['title'][0], 'The title must be at least 5 characters.');
        $this->assertEquals($messages['content'][0], 'The content field is required.');

        // search ViewErrorBag in google
    }

    public function test_update_valid() {

        $user = $this->user();
        $post = $this->create_dummy_blog_post($user);

        $this->assertDatabaseHas('blog_posts', $post->toArray());

        $params = [
            'title' => 'A new named title',
            'content' => 'Content was changed'
        ];

        $this->actingAs($user)
            ->put("/posts/{$post->id}", $params)
            ->assertStatus('302')
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog post was updated!');
        $this->assertDatabaseMissing('blog_posts', $post->toArray());
        // $this->assertSoftDeleted('blog_posts', $post->toArray());
        // $this->assertDatabaseHas('blog_posts', [
        //     'title' => 'A new named title',
        //     'content' => 'Content was changed'
        // ]);
    }

    public function test_delete() {
        $user = $this->user();
        $post = $this->create_dummy_blog_post();

        $this->assertDatabaseHas('blog_posts', $post->toArray());

        $this->actingAs($user)
            ->delete("/posts/{$post->id}")
            ->assertStatus('302')
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog post was deleted!');
        $this->assertDatabaseMissing('blog_posts', $post->toArray());
    }

    private function create_dummy_blog_post($userId = null): BlogPost{ // type
        $post = new BlogPost();
        $post->title = 'New title';
        $post->content = 'Content of the blog post';
        $post->save();

        // see BlogPostFactory.php's supended() method
        return BlogPost::factory()->new_title()->create(
            [
                'user_id' => $userId ?? $this->user()->id,
            ]
        );

        return $post;
    }
}
