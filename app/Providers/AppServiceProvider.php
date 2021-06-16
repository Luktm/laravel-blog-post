<?php

namespace App\Providers;

use App\Http\ViewComposers\ActivityComposer;
use App\Models\BlogPost;
use App\Models\Comment;
use App\Observers\BlogPostObserver;
use App\Observers\CommentObserver;
use App\Services\Counter;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

use App\Http\Resources\Comment as CommentResource;
use Illuminate\Http\Resources\Json\JsonResource;

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

        BlogPost::observe(BlogPostObserver::class);
        Comment::observe(CommentObserver::class);

        // open Counter.php, it a for Services Container to use in bind()
        // if you like to pass something $app, basically the $app will always be passed by Laravel to Disclosure
        // * use resolve(Counter::class) to Controller without create an instance new Counter(data);
        // register like getIt singleton here, register one instance here and use it everywhere
        // every call of resolve(Counter::class) for bind() will execute this brand new Counter
        // every call of resolve(Counter::class) for singleton() only run once and always be the same

        $this->app->singleton(Counter::class, function($app) {
            // return new Counter(random_int(0, 100)); // 5 second timeout
            return new Counter(
                $app->make('Illuminate\Contracts\Cache\Factory'), // $app->make just basically the same things as resolve helper function. resolve() is like getIt in flutter.
                $app->make('Illuminate\Contracts\Session\Session'), // use the interface Factory and Session by control click source Illuminate
                env("COUNTER_CHANNEL")
            ); // 5 second get from .env file
        });

        $this->app->bind(
            'App\Contracts\CounterContract', // bind abstract class to specify Service, else it will return error
            Counter::class
        );

        CommentResource::withoutWrapping(); // this only remove "data": wrapping json for particular resource
        // JsonResource::withoutWrapping(); // this remove all "data": for all api return json

        // $this->app->bind(
        //     'App\Contracts\CounterContract',
        //     DummyCounter::class
        // );

        // ? this only use for need primitive value in __construct() parameter
        // // alternative for checking Counter __construct parameter requirement
        // // and this is now not a singleton
        // $this->app->when(Counter::class)
        //     ->needs('$timeout') // parameter in __construct()
        //     ->give(env("COUNTER_TIMEOUT")); // then assign env value to it

    }
}
