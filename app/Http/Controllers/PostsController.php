<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

// use Illuminate\Support\Facades\DB;

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

        return view(
            'posts.index',
            // withCount only fetch the got relation between one another.
            // if wonder why orderBy desc is globally,
            // global query LatestScope.php and BlogPost.php line 39.
            // local query scopeLatest in Comment.php
            [
                'posts' => BlogPost::latest()->withCount('comments')->get(),
                // find in BlogPost.php at line 42 scopeMostCommented, but 'scope' will remove automatically
                'mostCommented' => BlogPost::mostCommented()->take(5)->get(),
                // find in User.php at line 51
                'mostActive' => User::withMostBlogPosts()->take(5)->get(),
                'mostActiveLastMonth' => User::withMostBlogPostsLastMonth()->take(5)->get(),
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
        $this->authorize('posts.create');
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
        $post = new BlogPost();
        // $post->title = $request->input('title');
        // $post->content = $request->input('content');

        // Custom example validator layouts.app
        // $post->title = $validatedData['title'];
        // $post->content = $validatedData['content'];
        // $post->save();

        // equivalent as above, also see BlogPost.php fillable fields
        $post = BlogPost::create($validatedData);

        //$post2 = BlogPost::make(); // create will save it, make need call save again
        // $post2->save();

        // can render in view layout.app about status
        $request->session()->flash('status', 'The blog post was created');

        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // abort_if(!isset($this->posts[$id]), 404);
        return view('posts.show', [
            'post' => BlogPost::with('comments')->findOrFail($id)

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
        $this->authorize('update', $post);

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

        // alternative Gate::denies, find it from AuthServiceProvider and BlogPostPolicy.php
        $this->authorize('posts.update', $post);


        $validatedData = $request->validated(); // return arrary with validated data
        $post->fill($validatedData); // fill the column name in BlogPost fillable[]
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

        // alternative Gate::denies, find it from AuthServiceProvider and BlogPostPolicy.php
        $this->authorize('delete', $post);

        $post->delete();

        // equivalent to $request->session() as above
        session()->flash('status', 'Blog post was deleted!');
        return redirect()->route('posts.index');
    }
}
