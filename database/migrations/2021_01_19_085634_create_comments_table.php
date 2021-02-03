<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();


             // little hack episode 119
            if( env('DB_DATABASE') === 'sqlite_testing') {
                $table->text('content')->nullable();
            } else {
                $table->text('content');
            }

            $table->unsignedBigInteger('blog_post_id')->index();
            $table->foreign('blog_post_id')->references('id')->on('blog_posts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
