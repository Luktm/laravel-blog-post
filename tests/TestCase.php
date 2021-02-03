<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // use in PostTest.php as it has exteded TestCase
    protected function user() {
        return User::factory()->create();
    }
}
