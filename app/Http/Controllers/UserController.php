<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUser;
use App\Models\Image;
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
    public function update(UpdateUser $request, User $user)
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
