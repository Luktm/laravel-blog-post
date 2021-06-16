<?php

use App\Http\Controllers\Api\V1\PostCommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// * luk: web.php, api.php, RouteServiceProvider.php, Kernel.php, auth.php for API purpose
// php artisan route:list | grep api, only grab api routes
// php artisan route:list | findstr "api"
// this should work closely with api.php "guard" => ["web"=>[], "api" => []]
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// prefix the routes with the version, use group to make it clean.
// php artisan route:list | grep api, to see prefix work api/v1/status,
// * api/ this prefix was taken from RouteServiceProvider.php boot() method Route::prefix('api')->xxxx
// * give it a parent name(api.v1.), child name("status") it will auto combine become api.v1.status
// namespace(Api\V1) the path from Http\Controllers\Api\V1
Route::prefix("v1")->name("api.v1.")->group(function () {
    Route::get("/status", function() {
        return response()->json(["status" => "OK"]);
    })->name("status");

    // * import from Controller/Api/V1
    // run php artisan route:list to see how does url of posts.comments look like when use this name,
    // posts/{post}/comments/{comment} => posts and laravel know to remove s to add posts/{post} and,
    // . is slash /
    // posts/{post}/comments/{comment} =>  /{comment} wildcard laravel know which method to have show(), update() and destroy() gonna have it
    Route::apiResource("posts.comments", PostCommentController::class);
});

Route::prefix("v2")->name("api.v2.")->group(function () {
    Route::get("/status", function() {
        return response()->json(["status" => true]);
    });
});

// https://laravel.com/docs/8.x/routing#fallback-routes
// it's better to put at the last line
// better handling of error message responses
Route::fallback(function () {
    // when url not valid
    // * go to Handler.php in app/Exceptions/*
    return response()->json([
        "message" => "Not found"
    ], 404);
})->name("api.fallback");
// try get the empty comment, it will fallback to "message": "Not found" route
