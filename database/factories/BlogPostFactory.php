<?php

namespace Database\Factories;

use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogPostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BlogPost::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(10),
            'content' => $this->faker->paragraphs(5, true),
        ];
    }


    // laravel will use new_title provided array, if not provide it will use above
    public function new_title()
    {
        return $this->state(function (array $attributes) {
            return [
                'title' => 'New title',
                'content' => 'Content of the blog post',
            ];
        });
    }
}
