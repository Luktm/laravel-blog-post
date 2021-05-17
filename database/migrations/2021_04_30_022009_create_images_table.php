<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string("path");
            // foreign blog post id
            $table->unsignedBigInteger('blog_post_id')->nullable(); //nullable mean it can accept empty/null
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
        Schema::dropIfExists('images');
    }
}
