<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// php artisan make:migration AddLocaleToUsersTable
class AddLocaleToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // locale column with 3 character accept, set default value the en
            $table->string("locale", 3)->default("en");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    // php artisan migrate:rollback will call this function
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {

        });
    }
}
