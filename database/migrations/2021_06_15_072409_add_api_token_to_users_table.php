<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * php artisan make:migration AddApiTokenToUsersTable
 * but it's only for laravel 5.x and 6.x
 * for laravel 8 use passport https://laravel.com/docs/8.x/passport#introduction for api authentication
 */
class AddApiTokenToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * this is from tutorial old laravle method
         * and also modify UserFactory.php seeder
         */
        Schema::table('users', function (Blueprint $table) {
            $table->string("api_token", 80)->after("password")
                ->unique()
                ->nullable()
                ->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn("api_token");
        });
    }
}
