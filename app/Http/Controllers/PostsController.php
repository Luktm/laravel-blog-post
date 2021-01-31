<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{

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
            ['posts' => BlogPost::withCount('comments')->get()]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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

        // Custom validator assign to variable with array contain
        $validated = $request->validated();
        $post = new BlogPost();
        // $post->title = $request->input('title');
        // $post->content = $request->input('content');

        // Custom example validator layouts.app
        // $post->title = $validated['title'];
        // $post->content = $validated['content'];
        // $post->save();

        // equivalent as above, also see BlogPost.php fillable fields
        $post = BlogPost::create($validated);

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

        return view('posts.show', ['post' => BlogPost::with('comments')->findOrFail($id)]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('posts.edit', ['post' => BlogPost::findOrFail($id)]) ;
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
        $validated = $request->validated(); // return arrary with validated data
        $post->fill($validated); // fill the column name in BlogPost fillable[]
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
        $post->delete();

        // equivalent to $request->session() as above
        session()->flash('status', 'Blog post was deleted!');
        return redirect()->route('posts.index');
    }
}