<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// php artisan make:migration RenameBlogPostTagTableToTaggables --table=blog_post_tag
// create a new migrate for blog_post_tag table

class RenameBlogPostTagTableToTaggables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_post_tag', function (Blueprint $table) {
            $table->dropForeign(["blog_post_id"]); // drop a full name of the foreign key instead of rename it else laravel will get lost where to delete
            $table->dropColumn("blog_post_id"); // after dropForeign key, let's remove the blog_post_id column

        });

         //rename blog_post_tag table to taggables table
        Schema::rename("blog_post_tag", "taggables");

        // since it has renamed, it is a whole new table, so we carry on the configuration in this schemma::table("new_table_name")
        Schema:: table("taggables", function(Blueprint $table) {
            $table->morphs("taggable"); // auto prefix taggable_type & taggable_id, don't do it in blog_post_tag schema, or else it will failed to rollback as the dropMorphs was pointed to taggables table
        });
    }

    /**
     * Reverse the migrations.
     *  php artisan migrate:rollback
     * @return void
     */
    public function down()
    {
        // since we no longer have blog_post_tag, we change it to taggables
        Schema::table('taggables', function (Blueprint $table) {
            $table->dropMorphs("taggable"); // drop taggable from line 27, taggable_id and taggable_type will be removed

        });

         //rename blog_post_tag table to taggables table
        Schema::rename("taggables", "blog_post_tag"); // rename back to blog_post_tag table

        Schema::disableForeignKeyConstraints(); // disable the column has constraint with the foregin key

        Schema::table("blog_post_tag", function (Blueprint $table) {
            $table->unsignedBigInteger('blog_post_id')->index();
            $table->foreign("blog_post_id")->references("id")->on("blog_posts")
                    ->onDelete('cascade');// when this is deleted we should also cascade, this will delete the row which is associated with the blog_post_tag
        });

        Schema::enableForeignKeyConstraints(); // renable them
    }
}

// php artisan migrate
