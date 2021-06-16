<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\CommentPosted;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreComment;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use App\Http\Resources\Comment as CommentResource;
use App\Models\Comment;

/**
 * php artisan make:controller Api/V1/PostCommentController --api
 * for api must be forward slash to create controller for api
 * --api is like --resource for web.php, come with index() , show, update() and so forth
 * docs https://laravel.com/docs/8.x/passport
 */
class PostCommentController extends Controller
{
    /**
     * a middleware like PostCommentController.php in Controller folder __construct()
     */
    public function __construct()
    {
        /**
         * auth:api, see auth.php at lien 46 "guards"
         * set only store() need authenticate for api
         * so it return http standard and protected endpoint
         * ? only() also reflect to policy
         */
        $this->middleware('auth:api')->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     * run php artisan route:list | grep api
     * see api/v1/posts/{post}/ has {post} wildcard, so put it in index(BlogPost $post)
     *
     */
    public function index(BlogPost $post, Request $request)
    {

        // get the request from postman url url?per_page=5
        $perPage = $request->input("per_page") ?? 15; // don't convert it to (int), if request is empyt it turn out 0, ?per_page=0, zero item per page

        // in php array always return json {} format
        // remember laravel always wrapping key "data": [{"id": 303}] in this json
        // * put CommentResource::withoutWrapping(); at AppServiceProvider.php to make it to remove data: wrapping in json,
        // so it turn out like [{"id": 303}]
        return CommentResource::collection(
            // $post->comments()->with("user")->get()

            // for pagination purpose, this sure will get data: parameter
            // this contain "links": {"first", "last"} url to specify page parameter
            // "meta": {"to": 7, "total": 7} containing current page, how many item do we have,
            $post->comments()->with("user")->paginate($perPage)->appends(
                // * append is add additional parameter in "links": {} "http://127.0.0.1:8000/api/v1/posts/22/comments?per_page=15&page=1", <- mean 15 item per page at page 1
                // this is mean i want specify how many item per_page in certain page
                [
                    "per_page" => $perPage
                ]
            ) // parameter mean how many item you want to have in per page
            //finally paste this url http://127.0.0.1:8000/api/v1/posts/22/comments?page=2 to see second page
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * luk: remember request always come from http url wildcard
     * * run php artisan route:list to see what wildcard does url have {post} = BlogPost
     * * call post method, see it in php artisan route:list | grep api
     * * pass body json {"content": "hello"} into it $request will do it for us
     * * StoreComment request is a validation, if certain header not pass like user id token is not provided, it will failed,
     * * StoreComment or request only for post and put, bcuz they need body json update data
     */
    public function store(BlogPost $post, StoreComment $request) // StoreComment validation request also use in web.php PostCommentController in store method, so make it resemblance, reused it for validator
    {

        /**
         * check auth.php file at line 40 guards, and PostCommentController.php in __contruct() {} $this->middleware("auth:web"),
         *
         */
        // registered CommentPolicy to AuthServiceProvider.php to check what to authorize.
        // provide Comment::class laravel will guess what are you trying to do
        $this->authorize(Comment::class);

        // Comment::create(and passing array of data or attribute)
        // instead can actually call post comment relation itself
        // got to Comments.php adding $fillable = [], bcuz it get from $post->comments(),
        $comment = $post->comments()->create([
            // assign content, since we already use store request, it's already validate the data, if it invalid, it will return the previous page with error message which we can access inside the form
            "content" => $request->input("content"),
            // ? remember run migrate user table with api_token column, we set it in UserFactory.php and run php artisan db:seed
            "user_id" => $request->user()->id
        ]); // * it can actually return the created comment data which already saved

        // ? Mail delay Play around started here

        // * pass it to Mail send to CommentPosted.php
        // * send
        // Mail::to($post->user)->send(
        //     // new CommentPosted($comment)
        //     new CommentPostedMarkdown($comment) // episode 216, refer to commented-markdown.blade.php
        //     // after that go post a new comment Mailtrap website will receive a template from this page
        // );

        // * queue()
        // * alternatively line 37, put it on the queue() as , same as implements ShouldQueue in CommentPostedMarkdown.php
        // always open a "php artian queue:work" in terminal
        // so when user post a new comment terminal will do a job
        // Mail::to($post->user)->queue(
        //     // new CommentPosted($comment)
        //     new CommentPostedMarkdown($comment) // episode 216, refer to commented-markdown.blade.php
        //     // after that go post a new comment Mailtrap website will receive a template from this page
        // );

        // why this happend, find it from app/Event and app/Listener,
        // ? dispatch event from CommentPosted.php event, not mail
        // see php artisan command inside CommentPosted.php how to create event and listener
        event(new CommentPosted($comment)); // import from Event
        // ? dipatch on event something has happened and then handle that in the other listener NotifyUsersAboutComment.php place.

        // ? once done api_token column migration into user table, set fake string in UserFactory.php, run php artisan db:seed
        // then return serialize json data mean encode it,
        // * 1st method paste url http://127.0.0.1:8000/api/v1/posts/17/comments?api_token=TzFoIzOzShLhbUwiLkj4wtR9QaSRXAMQiK5glYP0XJZz2XHlbDm1zRMoQ7sKZUl0a7m7X1TtkgGY3ufD,
        // * 2nd method paste header Bearer token
        // * remember api/v1 prefix from api.php, RouteServiceProvider.php line 42 Route::prefix('api'),
        // run postman to store it into comment table in database
        // middleware auth.php guard variable,
        // not data: wrapping see AppServiceProvider CommentResource::withoutWrapping()
        // status code 201 is creating resource
        return new CommentResource($comment);

        // * luk added in auth.php line 52, so it accept http://127.0.0.1:8000/api/v1/posts/17/comments?api_token=value
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * php artisan route:list to see which wildcard avaialble {post} and {comment} and get or post method in url
     * paste this to postman http://127.0.0.1:8000/api/v1/posts/17/comments/2
     */
    public function show(BlogPost $post, Comment $comment)
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     *  php artisan route:list to see which wildcard avaialble {post} and {comment} and get or post method in url
     */
    // public function update(Request $request, $id)
    public function update(BlogPost $post, Comment $comment, StoreComment $request)
    {
        // registered CommentPolicy to AuthServiceProvider.php to check what to authorize.
        $this->authorize($comment);
        // update comment content column, input from StoreComment html tag or body json {"content": "hello world"}
        $comment->content = $request->input("content");
        // save to database
        $comment->save();

        // return json to see updated content
        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * php artisan route:list to see which wildcard avaialble {post} and {comment} and get or post method in url
     */
    public function destroy(BlogPost $post, Comment $comment)
    {
        // registered CommentPolicy to AuthServiceProvider.php to check what to authorize.
        $this->authorize($comment);
        $comment->delete(); // laravel know which comment to delete
        // noContent is status code 200
        return response()->noContent();
    }
    // * and call it in api.php Route::apiResource("name.name");
}
