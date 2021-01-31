<?php

namespace Tests\Feature;


use Tests\TestCase;

class HomeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function text_home_page_is_working_correctly()
    {
        $response = $this->get('/');

        $response->assertSeeText('Hello world!');
        $response->assertSeeText('The current value is 0');
    }

    public function test_contact_page_is_working_correctly() {
        $response = $this->get('/contact');

        $response->assertSeeText('Contact');
        $response->assertSeeText('Hello this is contact');
    }
}
