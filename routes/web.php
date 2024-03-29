<?php

use App\Http\Controllers\AboutController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\PostTagController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserCommentController;
use App\Mail\CommentPostedMarkdown;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',[HomeController::class, 'home'])
    ->name('home.index')
    // ->middleware('auth')
    ;

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/contact', [HomeController::class, 'contact'])
    ->name('home.contact');

//name('') can up to us to naming it
Route::get('/secret', [HomeController::class, 'secret'])
    ->name('secret')
    // can:'s middleware for authorization system
    // middleware force only authorized can visit home.secret else it return 403 error
    ->middleware('can:home.secret');

    // the post name of the root parameter like
    // we have our roots for editing deleting posts
    // ->middleware('can:update.post');


// single action controller no need array by calling __invoked
Route::get('/single', AboutController::class);

// Route::view('/', 'home.index')
//     ->name('home.index'); // show only static page

// Route::view('/contact', 'home.contact')
//     ->name('home.contact'); // show only static page

$posts = [
    1 => [
        'title' => 'Intro to laravel',
        'content' => 'This is a short intro to Laravel',
        'is_new' => true,
        'has_comments' => true
    ],
    2 => [
        'title' => 'Intro to PHP',
        'content' => 'This is a short intro to PHP',
        'is_new' => false
    ],
    3 => [
        'title' => 'Intro to Golang',
        'content' => 'This is a short intro to Golang',
        'is_new' => false
    ]
];

// only([]) and except([]) will determine which CRUD to use, run "php artisan route:list" to see available CRUD
Route::resource('posts', PostsController::class);
// ->only(['index', 'show', 'create', 'store', 'edit', 'update']);

Route::get("posts/tag/{tag}", [PostTagController::class, "index"])->name("posts.tags.index");

// * why we doing this, bcuz of seperate the controller of UserController + UserCommentController and PostController + PostCommentController
// it call in blade file route("post.comments.index") or route("post.comments.store"), that's it
// run php artisan route:list see how does the url look like with this "posts.comments" and call it in postman,
// posts/{post}/comments/{comment} => posts and laravel know to remove s to add posts/{post} and,
// . is slash /
// posts/{post}/comments/{comment} =>  /{comment} wildcard laravel know which method to have show(), update() and destroy() gonna have it
Route::resource("posts.comments", PostCommentController::class)->only(["index", "store"]);
Route::resource("users.comments", UserCommentController::class)->only(["store"]); // so if type users/{user}/comments this sort of url, this web.php will redirect to this line instead of line 87
Route::resource("users", UserController::class)->only(["show", "edit", "update"]);

// this defined becuz of created mailtrap
Route::get("mailable", function() {
    $comment = Comment::find(1);
    return new CommentPostedMarkdown($comment);
    // this basically show the first comment
});

// use() pass the variable where the anonymous function variable is not set
// Route::get('/posts', function() use($posts) {
//     // dd(request()->all()); // return array with key

//     // cast number (int) if care it's number
//     dd((int)request()->query('page', 1)); //this is either querystring or form-data submission.
//     // dd((int)request()->input('page', 1)); //this is querystring
//     // compact method will produce array, assigned the key name with variable but without $ sign
//     // compact($posts) == ['posts' => $posts])

//     return view('posts.index', ['posts' => $posts]);
// });

// Route::get('/posts/{id}', function($id) use($posts) {
//     abort_if(!isset($posts[$id]), 404); // Invalid id not found return error 404

//     return view('posts.show', ['post' =>$posts[$id]]);
// })

// go RouteServiceProvider.php for global
// ->where([
//     'id' => '[0-9]+' // expect route receive number
// ])
// ->name('posts.show');

// ? GROUPING ROUTE
// Route::get('/recent-posts/{days_ago?}', function($days_ago = 20){
//     return 'Posts from ' . $days_ago . ' days ago';
// })->name('posts.recent.index')->middleware('auth');

// // url: prefix /fun/get(xx)|post(xx), name: prefix fun.name(xx)
// Route::prefix('/fun')->name('fun.')->group(function() use($posts) {

//     // response json
//     Route::get('/responses', function() use($posts) {
//         return response($posts, 201)
//             // ->view()
//             ->header('Content-Type', 'application/json')
//             // check in browser f12 -> appliction -> cookie tab
//             ->cookie('MY_COOKIE', 'Luk Tze Ming', 3600);
//     })->name('responses');

//     // redirect to new page
//     Route::get('redirect', function() {
//         return redirect('/contact');
//     })->name('redirect');

//     // back to latest page
//     Route::get('back', function() {
//         return back();
//     })->name('back');

//     // redirect and route with data given
//     Route::get('name-route', function() {
//         return redirect()->route('posts.show', ['id' => 1]);
//     })->name('name-route');

//     // redirect and away to external url
//     Route::get('away', function() {
//         return redirect()->away('https://google.com');
//     })->name('away');

//     // redirect and away to external url
//     Route::get('json', function() use($posts) {
//         return response()->json($posts);
//     })->name('json');

//     // redirect and away to external url
//     Route::get('download', function() use($posts) {
//         // public_path() will access to public folder
//         // return response()->download(public_path('/tony-stark.jpeg'), 'face.jpg', []); // [] <- this is additional header in third argument
//         return response()->download(public_path('/tony-stark.jpeg'), 'face.jpg'); // [] <- this is additional header in third argument
//     })->name('download');
// });



// ? api route go api.php to see it
