<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')
                ->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            // dropForeign first then dropColumn
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}
