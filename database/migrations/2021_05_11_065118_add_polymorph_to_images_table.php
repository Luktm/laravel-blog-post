<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// php artisan make:migration AddPolymorphToImagesTable
// episode 194 to create OneToOne Polymorphic migration
// instead of creating one to one relation, we can create a new table hold imageable_id and imageable_type, image(able_id) / image(able_type) is a convention to do it
// imageable_type will store different type of model
class AddPolymorphToImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropColumn("blog_post_id"); // drop blog_post_id column, since it doesn't have any foreign, we no need do anything else.
            // $table->unsignedBigInteger('imageable_id');
            // $table->string("imageable_type");

            $table->morphs("imageable");  // laravel feature automatically create {name}_id and {name}_type fields by it self

        });
    }

    /**
     * Reverse the migrat2ions.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('images', function (Blueprint $table) {
            // this should create back the column of blog_post_id, look back the first created image table migration, because it's a foreign key to prevent error occur when delete this particular image binded to that blog_post_id
            $table->unsignedBigInteger('blog_post_id')->nullable(); //nullable mean it can accept empty/null
            // drop against the morphs from up() method
            $table->morphs("imageable");
        });
    }
}

// add Schema::defaultStringLength(191) into AppServiceProvider.php
// php artisan migrate
