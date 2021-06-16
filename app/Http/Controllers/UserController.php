<?php

namespace App\Http\Controllers;

use App\Facades\CounterFacade;
use App\Http\Requests\UpdateUser;
use App\Models\Image;
use App\Models\User;
use App\Services\Counter;
use Illuminate\Http\Request;

// * "php artisan make:controller UserController --resource --model=User"
// * then run "php artisan make:policy UserPolicy --model=User"
// * and register UserPolicy in "AuthServiceProvider"
class UserController extends Controller
{
    // private $counter;

     // it will find a service container we have defined explicitly in AppServiceProvider $this->app->singleton()
    public function __construct(Counter $counter) // dependency injection episode 243
    {
        $this->middleware("auth"); // auth required
        $this->authorizeResource(User::class, "user"); // authorize certain action, it will use registered model policy for this particular model from UserPolicy.php as user()
        // $this->counter = $counter;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // pass cache to Services/Counter to make code cleaner
        // use resolve(), it's like getIt in flutter,
        // * since it had passed data to construtor into AppServiceProvider.php
        // $counter = resolve(Counter::class); // bcuz of dependency injection in __construtor(Counter counter), laravel auto assign



        // * remember in AuthServiceProvider at line 80, only admin can perform blog post update, delete ability.
        return view("users.show", [
            "user" => $user,
            // check how many user current view this profile, user->{$user->id} just a key to use for cache to recognize
            // $this->counter is equal to __construtor(Counter $counter) {$this->counter = $counter}
            // "counter" => $this->counter->increment("user->{$user->id}")
            // * alternative way of Contract is Facade, put the Contract in Facade extend facade,
            // and :: is static operator
            "counter" => CounterFacade::increment("user->{$user->id}")
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // * remember in AuthServiceProvider at line 80, only admin can perform blog post update, delete ability.
        return view("users.edit", ["user" => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, User $user) // ? do validation is better in Request UpdateUser.php class
    {
        // * remember in AuthServiceProvider at line 80, only admin can perform blog post update, delete ability.
        // * php artisan make:request UpdateUser and store it into image table
        if($request->hasFile("avatar")) {
            $path = $request->file("avatar")->store("avatars"); // get file form avatar input type and store it into avatars folder

            if($user->image) {
                $user->image->path = $path;
                $user->image->save();
            } else {
                // // althernative way
                // $image = new Image();
                // $image->path = $path;

                // best way, this make shorter
                $user->image()->save(
                    Image::make(["path" => $path])
                ); // this is one to one relation or polymorphic
            }
        }

        // new migration added locale column to use,
        // we certain that use has submit language from validator, and get it the valid data from request
        // only we using [ Rule::in(array_keys(User::LOCALES)) ] in UpdateUser request has to use get() where locale name get from <form><select name="locale" id=""></select</form>;
        $user->locale = $request->get("locale");
        $user->save();


        // * after save it refresh data mean recall PostsController.php of __construct(),
        // * where has middleware of LocaleMiddleware.php registered in Kernel.php and,
        // * call the $this->middleware("key in Kernel.php $routeMiddleware") in PostsController.php of __construct() method

        // same as $request->session()->flash('status', 'Blog post was updated!');
        return redirect()
            ->back()
            ->withStatus("Profile image was updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
