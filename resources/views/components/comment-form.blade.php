<div class="mb-2 mt-2">

    {{-- SLOT WILL PASS DOWN TO HERE WHERE THE @commentForm(["route" => "abcdefgh"], [post=> $post->id]}) @endcommentForm being use--}}

    {{-- only same user id / admin for this post able delete else hide delete button --}}
    {{-- go AuthServiceProvider.php line 20, bcuz it's declared, we can just call delete or posts.delete in Gate::resource('posts') --}}
    {{-- softdelete directive, display trash post as well --}}
    {{-- @auth check has authenticatad or not --}}
    @auth
        {{-- if user had login will see this textarea --}}
        {{-- run php artisan route:list get posts.comments.store from there and pass the data to web.php's PostCommentController.php's store() --}}
        {{-- <form method="POST" action="{{ route("posts.comments.store", ["post" => $post->id]) }}"> --}}
            {{-- watch episode 202 add new Blade::component to AppServiceProvider --}}
        <form method="POST" action="{{ $route }}">
            @csrf
            <div class="form-group">
                <textarea type="text" name="content" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                Add Comment
            </button>
        </form>
        {{-- find this alias component at AppServiceProvider.php at line 35 --}}
        @errors @enderrors
        {{-- if not login  do something --}}
    @else
        <a href="{{ route('login') }}">Sign-in</a> to post comments
    @endauth
</div>
<hr />
