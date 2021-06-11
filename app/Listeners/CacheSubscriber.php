<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

// * remember import this for subscribers the laravel own class to cache, subscribers not need event
use Illuminate\Cache\Events\CacheHit;
use Illuminate\Cache\Events\CacheMissed;
use Illuminate\Support\Facades\Log;

// * php artisan make:listener CacheSubscriber
// * for logging.php and storage/logs purpose
class CacheSubscriber
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handleCacheHit(CacheHit $event) // rename method corresponde to parameter
    {
        // "{}" is like "${}" in flutter
        Log::info("{ $event->key } cache hit");
    }

    public function handleCacheMissed(CacheMissed $event)
    {
        Log::info("{ $event->key } cache miss");
    }



    // this method is kind of handle() method
    public function subscribe($events)
    {
        // set listener, for config/loging.php, so it will send notfication
        // https://laravel.com/docs/8.x/events
        $events->listen(
            CacheHit::class, // laravel own cache class
            [CacheSubscriber::class, "handleCacheHit"] // call self class and method above
        );

        $events->listen(
        CacheMissed::class, // laravel own cache class
            [CacheSubscriber::class, "handleCacheMissed"] // call self class and method above
        );

        // ? after that register in EventServiceProvider.php create a new protected variable "$subscribe=>[]"
        // ? then go click any blog post to see the cache in storage/log
    }
}
