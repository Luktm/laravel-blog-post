<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCascadeDeleteToCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['blog_post_id']);

            // for unit test
            if(env('DB_DATABASE') === 'sqlite_testing') {
                 // recreate foreign key after dropForeign
                $table->foreign('blog_post_id')
                    ->references('id')
                    ->on('blog_posts')

                    // it will be different, we want to cascade it because we delete from database level
                    // see BlogPost.php it equivalent to static::deleting() so we commented out that
                    // The onDelete('cascade') means that when the row is deleted, it will delete all it's references and attached data too. For example if you have a User which has a Post and you set onDelete('cascade') on the user, then when the user deletes his account, all posts will be deleted as well
                    ->onDelete('cascade');
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
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['blog_post_id']);
            $table->foreign('blog_post_id')
                    ->references('id')
                    ->on('blog_posts');
        });
    }
}