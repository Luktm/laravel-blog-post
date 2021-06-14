<?php

namespace App\Services;

// default one
// use Illuminate\Support\Facades\Cache;

// check this url https://laravel.com/docs/8.x/contracts

use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Contracts\Session\Session; // click inside
use App\Contracts\CounterContract;

// no command, just create Services/Counter.php manully and,
// Service is call once instantiate and use it everywhere by calling resolve(Counter::class), something like getIt
// * to keep it easy to change, put it in AppServiceProvider or you can create a new provider
class Counter implements CounterContract // abstract class, but must bind() it in AppServiceProvider.php line 71
{

    private $timeout;

    // see line 8 to 10, just for best practice, watch espisode 224
    private $cache;
    private $session;
    private $supportsTags;
    // * after that inject

    // passed from AppServiceProvider.php
    // $cache passed $app->make('Illuminate\Contracts\Cache\Factory'), it's like getIt
    // $session passed $app->make('Illuminate\Contracts\Session\Session'), it's like getIt
    public function __construct(Cache $cache, Session $session, int $timeout)
    {
        $this->cache = $cache;
        $this->timeout = $timeout;
        $this->session = $session;
        $this->supportsTags = method_exists($cache, 'tags'); // method_exists check whether the object contain tags method
    }

    public function increment(string $key, array $tags = null): int
    {
         // epidose 162 store visited page number in post.show in PostController.php

        // dump($this->session);
        // dd($this->cache);
        // $sessionId = session()->getId(); // replace with Session import
        $sessionId = $this->session->getId();
        $counterKey = "{$key}-counter";
        $usersKey = "{$key}-users";

        // if $usersKey null return empty array []; epidose 162
        // only redis accept tags, since we use new contract at line 8 to 10

        // we check support tags where user has specify
        $cache = $this->supportsTags && null !== $tags
            ? $this->cache->tags($tags) : $this->cache;

        // $users = Cache::tags(["blog-post"])->get($usersKey, []);
        $users = $cache->get($usersKey, []);
        $usersUpdate = [];
        $difference = 0;
        $now = now();

        // loop epidose 162
        foreach($users as $session => $lastVisit) {
            // timeout set here
            if($now->diffInMinutes($lastVisit) >= $this->timeout) {
                $difference--;
            } else {
                $usersUpdate[$session] = $lastVisit;
            }
        }

        // $difference increment called here if user hasn't visited the page in the last mins. epidose 162
        if(
            !array_key_exists($sessionId, $users)
            ||
            $now->diffInMinutes($users[$sessionId]) >= 1
        ) {
            $difference++;
        }

        $usersUpdate[$sessionId] = $now;
        // put and forever store key infinitly. epidose 162
        // Cache::tags(["blog-post"])->forever($usersKey, $usersUpdate);
        $cache->forever($usersKey, $usersUpdate);

        // check $counterKey not exist. epidose 162
        // if(!Cache::tags(["blog-post"])->has($counterKey)) {
        if($cache->has($counterKey)) {
            // if user hasn't been on the page, it make sense to set $counterKey to 1. epidose 162
            // Cache::tags(["blog-post"])->forever($counterKey, 1);
            $cache->forever($counterKey, 1);
        } else {
            // if existed return integer by run increment if it's null
            // Cache::tags(["blog-post"])->increment($counterKey, $difference);
            $cache->increment($counterKey, $difference);
        }

        // this Cach::get($counterKey) sure exist, bcuz we did check if not exist set it to 1 as default value at line 194 to 196. epidose 162
        // this is final step. epidose 162
        // $counter = Cache::tags(["blog-post"])->get($counterKey);
        $counter = $cache->get($counterKey);

        return $counter;
    }
}

?>
