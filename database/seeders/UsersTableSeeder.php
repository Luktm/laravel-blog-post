<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // it will accept a string, put int infront convert to int, max will insert minimum 1
        $userCount = max((int)$this->command->ask('How many users would you like?', 20), 1);
        User::factory()->new_user()->create();

        User::factory()->count($userCount)->create();

    }
}
