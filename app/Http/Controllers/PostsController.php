<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

// use Illuminate\Support\Facades\DB;
// PostsController.php, we create seperate controller to PostCommentController.php
class PostsController extends Controller
{

    public function __construct(){
        // auth needed before user can access these page/function
        $this->middleware('auth')
            ->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    private $posts = [
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Eager Loading vs Lazy loading
        // DB::enableQueryLog();
        // $posts = BlogPost::with('comments')->get();

        // foreach($posts as $post) {
        //     foreach($post->comments as $comment) {
        //         echo $comment->content;
        //     };
        // }

        // dd(DB::getQueryLog());

        // comments_count

        // cache for 10 seconds, return the unique key mostCommented if less than 10 seconds
        // default accept as mins, or you can add now()->addSeconds(10) for only last 10 seconds cache
        // Cache:tags(["key"]) just a parent representative for easy flush away it episode on 165, then remember run "php artisan db:seed"
        // Once added Cache:tags([]) remember go DatabaseSeeder.php add Cache::tags([])->flush(); and run again "php artisan db:seed"

        // ATTENTION: CREATE NEW FOLDER CALLED ViewComposers AND MIGRATE ALL mostCommented, mostActive, mostActiveLastMonth THIS TO THAT PAGE
        // AND FIND THIS IN AppServiceProvider.php at line 37 and ActivityComposer.php and index.blade.php replacement. Please watch episode 173

        // $mostCommented = Cache::tags(["blog-post"])->remember('mostCommented', 60, function () {
        //     return BlogPost::mostCommented()->take(5)->get();
        // });

        // $mostActive = Cache::remember('mostActive', now()->addSeconds(10), function () {
        //     return User::withMostBlogPosts()->take(5)->get();
        // });

        // $mostActiveLastMonth = Cache::remember('mostActiveLastMonth', now()->addSeconds(10), function () {
        //     return User::withMostBlogPostsLastMonth()->take(5)->get();
        // });

        return view(
            'posts.index',
            // withCount only fetch the got relation between one another.
            // if wonder why orderBy desc is globally,
            // global query LatestScope.php and BlogPost.php line 39.
            // local query scopeLatest in Comment.php
            [
                // with(function inside BlogPost.php) is to reduce query oftentime in a way to save server load
                // 'posts' => BlogPost::latest()->withCount('comments')
                //     ->with('user')->with("tags")->get(),

                // this get from BlogPost.php at line 106
                'posts' => BlogPost::latestWithRelations()->get()

                // find in BlogPost.php at line 42 scopeMostCommented, but 'scope' will remove automatically

                // Episode 173, it help to remove hassle passed empty array in PostTagController.php
                // 'mostCommented' => $mostCommented,
                // // find in User.php at line 51
                // 'mostActive' => $mostActive,
                // 'mostActiveLastMonth' => $mostActiveLastMonth,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // see AuthServiceProdider.php & BlogPostPolicy.php
        // this authorize posts.create was not equivalent to view('post.create)
        $this->authorize('posts.create'); // from BlogPostPolicy.php
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // don't forget StorePost <input name="thumbnail"/> was inside to validate file type
    public function store(StorePost $request)
    {
        // validator can be pipe to avoid error page
        // bail will stop the rest of running
        // go Kernel.php see ShareErrorsFromSession
        // See Request->StorePost()'s rule method

        // $request->validate();

        // Custom validator assign to variable with array contained
        $validatedData = $request->validated();
        // get user_id from BlogPost.php fillable and assign user id
        $validatedData['user_id'] = $request->user()->id;
        // $post->title = $request->input('title');
        // $post->content = $request->input('content');

        // Custom example validator layouts.app
        // $post->title = $validatedData['title'];
        // $post->content = $validatedData['content'];
        // $post->save();

        // equivalent as above, also see BlogPost.php fillable fields
        $blogPost = BlogPost::create($validatedData);

        // display request object from form.blade.php multipart/form-data's thumbnail input tag
        // dump($request->hasFile("thumbnail"));
        // die; // stop here

        // $hasFile = $request->hasFile("thumbnail");
        // dump($hasFile);
        if($request->hasFile("thumbnail")){
            // store in thumbnail folder
            $path = $request->file("thumbnail")->store("thumbnails"); // it will return path name to be stored, and run "php artisan make:model Image --migration"
            // dump($file);
            // dump($file->getClientMimeType());
            // // what extension specify of that file
            // dump($file->getClientOriginalExtension());

            // // create thumbnail folder store it into storage folder > filesystem.php config from .env last line code
            // dump($file->store("thumbnails"));

            // // episode 184
            // dump(Storage::disk("public")->put($file, "thumbnails"));

            // // storeAs able to set a custom name such as "thumbnails/31.jpg" but it didn't contain any additional information get from filesystem.php
            // $name1 = $file->storeAs("thumbnails", $post->id . "." . $file->guessExtension());

            // // Storage::disk("local") was save in local and was set in the filesystem.php at line 35, something like storage/app/thumbnails
            // $name2 = Storage::disk("local")->put($post->id . "." . $file->guessExtension(), "thumbnails");

            // dump(Storage::url($name1)); // copy this url and paste in new tab, it will display the image only by run "php artisan storage:link" to create a link itself to app/public
            // dump(Storage::path($name2));
            // therefore, at filesystems.php public->url->APP_URL has to change in .env at line 5

            // image() function get from blogPost.php and save will create a blog post where image will be created with blog_post_id column correspond to created blog post's id
            $blogPost->image()->save(
                // string path url will be created and save into path column for image table
                // Image::create(["path" => $path])
                Image::make(["path" => $path]) //  we can't save this new image model without assigning first those two fields. We can't leave imageable_id and imageable_type empty, this the way to solve it
            );
        }
        // die;
        //$post2 = BlogPost::make(); // create will save it, make need call save again
        // $post2->save();

        // can render in view layout.app about status
        $request->session()->flash('status', 'The blog post was created');

        // route to web.php > posts.show || App\Http\Controllers\PostsController@show
        return redirect()->route('posts.show', ['post' => $blogPost->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Cache::tags() can be a parent tag
        // this has to be dynamic key else user always see the same page
        $blogPost = Cache::tags(["blog-post"])->remember("blog-post-{$id}", 60, function () use($id){
             // with() is to reduce query oftentime in a way to save server load
            //  fetch those relation data wherej written functions in BlogPost.php and pass into show.blade.php at line 69
            return BlogPost::with('comments', "tags", "user", "comments.user")
                // optional
                // ->with("tags")
                // ->with("user")
                // // dot mean nested relationship
                // ->with("comments.user")
                ->findOrFail($id);
        });

        // epidose 162 store visited page number in post.show in PostController.php
        $sessionId = session()->getId();
        $counterKey = "blog-post-{$id}-counter";
        $usersKey = "blog-post-{$id}-users";

        // if $usersKey null return empty array []; epidose 162
        $users = Cache::tags(["blog-post"])->get($usersKey, []);
        $usersUpdate = [];
        $difference = 0;
        $now = now();

        // loop epidose 162
        foreach($users as $session => $lastVisit) {
            if($now->diffInMinutes($lastVisit) >= 1) {
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
        Cache::tags(["blog-post"])->forever($usersKey, $usersUpdate);

        // check $counterKey not exist. epidose 162
        if(!Cache::tags(["blog-post"])->has($counterKey)) {
            // if user hasn't been on the page, it make sense to set $counterKey to 1. epidose 162
            Cache::tags(["blog-post"])->forever($counterKey, 1);
        } else {
            // if existed return integer by run increment if it's null
            Cache::tags(["blog-post"])->increment($counterKey, $difference);
        }

        // this Cach::get($counterKey) sure exist, bcuz we did check if not exist set it to 1 as default value at line 194 to 196. epidose 162
        // this is final step. epidose 162
        $counter = Cache::tags(["blog-post"])->get($counterKey);

        // abort_if(!isset($this->posts[$id]), 404);
        return view('posts.show', [
            'post' => $blogPost,
            'counter' => $counter, // epidose 162

            // scopeLatest in Comment.php fetch comment from new to old
            // or can do it in BlogPost.php hasMany()->latest()
            // 'post' => BlogPost::with(['comments' => function ($query) {
            //     return $query->latest();
            // }])->findOrFail($id)
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);
        // from 'posts.update' to 'update', see AuthServiceProvider line 20
        $this->authorize('update', $post); // authorize certain action before run the next line

        return view('posts.edit', ['post' => $post]) ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, $id)
    {
        $post = BlogPost::findOrFail($id);



        // Check inside Povider->AuthServiceProvider.php see whether
        // that user is eligible to edit the post base on their id
        // and use it in update().
        // if(Gate::denies('update-post', $post)) {
        //     // abort() function will redirect to error page
        //     abort(403, "you can't edit this blog post!");
        // }

        // alternative Gate::denies from above line, find it from AuthServiceProvider and BlogPostPolicy.php
        $this->authorize('posts.update', $post);


        $validatedData = $request->validated(); // return array with validated data ["title"=> "new text", "content"=> "new text"]
        $post->fill($validatedData); // fill the column name in BlogPost.php fillable[]
        // dd($validatedData);
        // check has thumbnail file, update() post can actually store as same as store()
        // don't forget StorePost <input name="thumbnail"/> was inside to validate file type
        if($request->hasFile("thumbnail")) {
            $path = $request->file("thumbnail")->store("thumbnails");

            // check if the post has already have image
            if($post->image) {
                // delete old image, but soft delete it, go to BlogPost.php at boot()'s static::deleting and enable it at line 137 "$blogPost->image()->delete()"
                Storage::delete($post->image->path);
                // assign new image
                $post->image->path = $path;
                // save image
                $post->image->save();
            } else {
                $post->image()->save( // so imageable_id and imageable_type will be set automatically by calling $post->image()->save()
                    // Image::create(["path" => $path])// this will immediately save to database
                    Image::make(["path" => $path])//  we can't save this new image model without assigning first those two fields. We can't leave imageable_id and imageable_type empty, this the way to solve it
                );
            }


        }

        $post->save();

        // render status to view
        $request->session()->flash('status', 'Blog post was updated!');

        // redirect with update id pass down
        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);

        // if(Gate::denies('delete-post', $post)) {
        //     // abort() function will redirect to error page
        //     abort(403, "you can't delete this blog post!");
        // }

        // alternative Gate::denies, find it from AuthServiceProvider Gate::resources("post", []) and BlogPostPolicy.php
        // check wheather the use is eligible for delete the post
        $this->authorize('delete', $post); // "delete from BlogPostPoclicy delete() to see is it the user himself delete" the post?

        $post->delete();

        // equivalent to $request->session() as above
        session()->flash('status', 'Blog post was deleted!');
        return redirect()->route('posts.index');
    }
}
