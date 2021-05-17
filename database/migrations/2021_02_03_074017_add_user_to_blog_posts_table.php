<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// php artisan make:migration add_user_to_blog_posts_table --table=blog_posts // --table=users mean it will bind to Schema::table("blog_posts")
// php artisan make:migration add_user_to_blog_posts_table #mean it will add user_id column into blog_post table
class AddUserToBlogPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            // nullable will insert null to existing data
            // $table->unsignedBigInteger('user_id')->nullable();

            if( env('DB_DATABASE') === 'sqlite_testing') {
                $table->unsignedBigInteger('user_id')->default(0);
            } else {
                $table->unsignedBigInteger('user_id');
            }

            $table->foreign('user_id')
                ->references('id')->on('users');
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
        Schema::table('blog_posts', function (Blueprint $table) {
            // dropForeign first then dropColumn
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}
