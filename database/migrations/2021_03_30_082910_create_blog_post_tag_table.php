<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogPostTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // "php artisan make:migration CreateBlogPostTagTable", create and table will remove here
        Schema::create('blog_post_tag', function (Blueprint $table) {
            $table->id();

            // unsignedBigInteger(name can be random, must must match with foreign()) must combine with foreign()
            $table->unsignedBigInteger('blog_post_id')->index();
            // foreign(name can be random), reference to id from blog_posts table, onDelete will remove the relation when delete
            $table->foreign('blog_post_id')->references('id')->on('blog_posts')->onDelete('cascade');

            $table->unsignedBigInteger('tag_id')->index();
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');

            $table->timestamps();
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
        Schema::dropIfExists('blog_post_tag');
    }
}
