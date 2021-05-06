<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

// * "php artisan make:controller UserController --resource --model=User"
// * then run "php artisan make:policy UserPolicy --model=User"
// * and register UserPolicy in "AuthServiceProvider"
class UserController extends Controller
{
    public function __construct() {
        $this->middleware("auth"); // auth required
        $this->authorizeResource(User::class, "user"); // authorize certain action, it will use registered model policy for this particular model from UserPolicy.php as user()
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
        // * remember in AuthServiceProvider at line 80, only admin can perform blog post update, delete ability.
        return view("users.show", ["user" => $user]);
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
    public function update(Request $request, User $user)
    {
        // * remember in AuthServiceProvider at line 80, only admin can perform blog post update, delete ability.
        dd($user);
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
