<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// php artisan make:migration create_profiles_table
class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // // unsignedInteger mean start with zero with unique id column
            // $table->unsignedBigInteger('author_id')->unique();

            // // laravel will auto assign a name
            // $table->foreign('author_id')->references('id')->on('authors');
            $table->foreignId('author_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    // php artisan migrate:rollback will run down() method descendants
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
