<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// php artisan make:migration create_blog_posts_table
// create mean create a new table(not yet exist table in database)
class CreateBlogPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('title')->nullable();

            // little hack episode 119
            if( env('DB_DATABASE') === 'sqlite_testing') {
                $table->text('content')->nullable();
            } else {
                $table->text('content');
            }

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_posts');
    }
}
