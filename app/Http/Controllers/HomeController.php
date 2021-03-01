<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // luk: except() contact page will no need auth
        // $this->middleware('auth')->except('contact');
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // dd(Auth::check());
        // dd(Auth::id());
        // dd(Auth::user());
        return view('home');
    }

    public function home() {
        return view('home.index');
    }

    public function contact() {
        return view('home.contact');
    }

    public function secret() {
        return view('home.secret');
    }
}
