<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//  php artisan create_tags_table
class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // for many to many relation purpose
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string("name", 40);
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
        Schema::dropIfExists('tags');
    }
}
