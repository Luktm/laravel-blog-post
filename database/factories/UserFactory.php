<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * php artisan make:factory UserFactory --model=User
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        /**
         * run php artisan db:seed if added one or more things here
         */
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'api_token' => Str::random(80), // remember every user has unique token
            'remember_token' => Str::random(10),
            'is_admin' =>false,
        ];
    }

    // laravel overwrite above where array's data was given in the state
    public function new_user()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'John Doe',
                'email' => 'john@laravel.com',
                'is_admin' => true
            ];
        });
    }
}
