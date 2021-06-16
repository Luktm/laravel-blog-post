<?php

namespace Tests;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // use in PostTest.php as it has exteded TestCase
    protected function user() {
        return User::factory()->create();
    }

    protected function blog_post() {
        return BlogPost::factory()->create([
            "user_id" => $this->user()->id
        ]);
    }
}
