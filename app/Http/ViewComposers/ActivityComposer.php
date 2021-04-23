<?php
namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use App\Models\BlogPost;
use App\Models\User;

class ActivityComposer {
    // find in PostController.php line 69, basically it was a replacement
    public function compose(View $view) {
        $mostCommented = Cache::tags(["blog-post"])->remember('mostCommented', 60, function () {
            return BlogPost::mostCommented()->take(5)->get();
        });

        $mostActive = Cache::remember('mostActive', now()->addSeconds(10), function () {
            return User::withMostBlogPosts()->take(5)->get();
        });

        $mostActiveLastMonth = Cache::remember('mostActiveLastMonth', now()->addSeconds(10), function () {
            return User::withMostBlogPostsLastMonth()->take(5)->get();
        });

        // first argument will be the name variable eg use in index.blade.php such as $mostCommented, $mostActiveLastMonth
        $view->with("mostCommented", $mostCommented);
        $view->with("mostActive", $mostActive);
        $view->with("mostActiveLastMonth", $mostActiveLastMonth);
        // and then go AppServiceProvider.php to register blade component call view()->composer("")
    }
}

?>
