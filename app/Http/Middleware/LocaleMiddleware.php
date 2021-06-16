<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

// ? php artisan make:middleware LocaleMiddleware
// see app.php config, User::LOCALE variable User.php
class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // check request condition before call $next($request)
        $locale = null;

        // if the session doesn't have the locale already,
        // becuz we do not want to override it all the time,
        // so we use Session::has("key")
        // session is like consistence data, shared_preference or sqflite
        if(Auth::check() && !Session::has("locale")) {
            $locale = $request->user()->locale; // * current authenticated user | user table in locale column from database.
            // if the user isn't integrated and there is no local in his session yet,
            // then read the user preferred locale eloquent model as a user, as the local attribute.
            Session::put("locale", $locale); // store current user locale to Session key
        }

        // if session already has locale in shared_preference
        // ? All $request is always come from <form></form> tag
        if($request->has("locale")) { // * check locale <select name="locale"></select> in edit.blade.php.
            $locale = $request->get("locale"); // * get from user from <select name="locale"></select>
            Session::put("locale", $locale);
        }

        $locale = Session::get("locale");

        if(null === $locale) {
            // set default variable from app.php in config folder
            $locale = config("app.fallback_locale"); // app.php then fallback_locale from config folder user config() method
        }

        App::setLocale($locale); // to better understand middleware, open Kernel.php $routeMiddleware, set all the app with current selected locale lang

        return $next($request); // save selected lang to logon user into user table of locale column
    }
    // * and use in controller $this->middleware('locale') to run it first
    // * for initial language setup when user visit the website open .htaccess from public directory
}
