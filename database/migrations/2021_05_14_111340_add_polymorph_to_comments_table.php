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
        // it will rollback to no have morph OneToOne relation
        Schema::table('comments', function (Blueprint $table) {
            $table->dropMorphs("commentable");

            // rollback create blog_post_id again
            $table->unsignedBigInteger('user_id');
            $table->foreign("blog_post_id")->references("id")->on("blog_posts"); // foreign reference on id from blog_post table
        });
    }
}
