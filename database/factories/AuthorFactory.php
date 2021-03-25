<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Author::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }

    // Model factory callbacks
    public function configure() {
        return $this->afterMaking(function(Author $author) {
            // profile() found from Author.php and,
            // watch udemy episode 105
            $author->profile()->save(Author::factory()->make());
        })->afterCreating(function (Author $author) {
            $author->profile()->save(Author::factory()->make());
        });
    }
}
