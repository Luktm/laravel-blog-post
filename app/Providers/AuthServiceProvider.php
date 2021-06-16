<?php

namespace App\Providers;

use App\Models\BlogPost;
use App\Models\User;
use App\Policies\BlogPostPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
        // auto assign to $this->registerPolicies(),
        // it omit  $this->authorize('posts.update') to
        // * $this->authorize('update')
        'App\BlogPost' => 'App\Policies\BlogPostPolicy',
        'App\User' => 'App\Policies\UserPolicy', // and go web.php write Route.resource("users", )
        'App\Comment' => 'App\Policies\CommentPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // home.secret name is up to us to naming it and then use it and any blade.php @can("home.secret") eloquent
        Gate::define('home.secret', function($user) {
            return $user->is_admin;
        });

        // Specific which user can edit which post in global Gate, (User $user, BlogPost $post)'s post was
        // automatically pass in from Postcontroller.php's update() method

        // Gate::define('update-post', function(User $user, BlogPost $post) {
        //     return $user->id == $post->user_id;
        // });

        // Gate::allow('update-post', $post);
        // $this->authorize('update-post', $post); in PostController

        // Gate::define('delete-post', function(User $user, BlogPost $post) {
        //     return $user->id == $post->user_id;
        // });

        // any string pass in bracket, ability can access it
        // to create blogPostpocily with CRUD in it

        // run 'php artisan make:policy BlogPostPolicy --model=BlogPost'

        // once added go to PostController.php change $this->authorize()
        // Gate::define('posts.update', [BlogPostPolicy::class, 'update']);
        // Gate::define('posts.delete', [BlogPostPolicy::class, 'delete']);

        // alternative from above two posts.update and posts.delete
        // so prefix posts and resource will handle method inside of BlogPostPolicy::class
        Gate::resource('posts', BlogPostPolicy::class);
        // posts.create, posts.view, posts.update, posts.delete

        // Sometimes, you may wish to grant all abilities to a specific user.
        // You may use the before method to define a closure
        // that is run before all other authorization checks from the above
        // so admin can delete edit and update all blog post
        Gate::before(function($user, $ability){
            // ability allow admin update and delete post
            // if($user->is_admin && in_array($ability, ['posts.update'])) {
            //     return true;
            // }


            // line 20 added, so the name can be shorten
            if($user->is_admin && in_array($ability, ['update', 'delete'])) {
                return true;
            }
        });



        // after will be ball after Gate::define from above
        // Gate::after(function($user, $ability, $result){
        //     // ability allow admin update and delete post
        //     if($user->is_admin) {
        //         return true;
        //     }
        // });

    }
}
