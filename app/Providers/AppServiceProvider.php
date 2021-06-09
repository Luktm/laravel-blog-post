<?php

namespace App\Providers;

use App\Http\ViewComposers\ActivityComposer;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191); // from add_polymorphic_to_image_table
        // components.badge get from component > badge.blade.ph
        // give it a name and call @badge(['type' => 'primary']) directive
        // @components('components.badge') has to provide path but @badge([]) provide in AppServiceProvider
        Blade::aliasComponent('components.badge', 'badge'); // first parament is the file path, second parament is name to call
        Blade::aliasComponent('components.updated', 'updated');
        Blade::aliasComponent('components.card', 'card');
        Blade::aliasComponent('components.tags', 'tags');
        Blade::aliasComponent('components.errors', 'errors');
        Blade::aliasComponent('components.comment-form', 'commentForm');
        Blade::aliasComponent('components.comment-list', 'commentList');

        // find this from ActivityComposer.php, it pass ActivityComposer variable start in line 25 to specify view posts > index.blade.php & posts > partials > show.black.php
        view()->composer(["posts.index", "posts.show"], ActivityComposer::class);

        // this arterisk mean available to every single laravel view
        // view()->composer("*", ActivityComposer::class);
    }
}
