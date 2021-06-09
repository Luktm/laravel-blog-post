<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// * php artisan make:migration AddPolymorphToCommentsTable
class AddPolymorphToCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(["blog_post_id"]); // as we don't need them anymore
            $table->dropColumn("blog_post_id"); // as we don't need them anymore

            $table->morphs("commentable"); // it create commentable_id and commentable_type
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // php artisan migrate:rollback will call this down() method
        // it will rollback to no have morph OneToOne relation, simply put it undo one step.
        Schema::table('comments', function (Blueprint $table) {
            $table->dropMorphs("commentable");

            // rollback create blog_post_id again
            $table->unsignedBigInteger('blog_post_id')->index()->nullable(); // at next line foreign key will actually failed with no value, so we provide null() value to solve it

            $table->foreign("blog_post_id")->references("id")->on("blog_posts"); // foreign reference on id from blog_post table
        });
    }
}

// luk$ php artisan db:seed
