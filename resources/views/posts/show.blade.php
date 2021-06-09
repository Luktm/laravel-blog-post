@extends('layouts.app')

@section('title', $post->title)

@section('content')
{{-- @if($post['is_new'])
<div>A new blog post! Using if</div>
@elseif(!$post['is_new'])
<div>Blog post is old! Using elseif</div>
@endif

@unless($post['is_new'])
<div>It is an old post... using unless</div>
@endunless --}}
<div class="row">
    <div class="col-8">

        {{-- post->image get from blogpost.php image() and url was from blogpost.php->image(from Image.php)->url() --}}
        @if($post->image)
            <div style="background-image: url('{{ $post->image->url() }}'); min-height: 500px; color: white; text-align: center; background-attachment: fixed;">
                <h1 style="padding-top: 100px; text-shadow: 1px 2px #000;">
        @else
            <h1>
        @endif
            {{ $post->title }}
            {{-- component('import first name blade file') like react import component, second argument was ${{type}} --}}

            {{-- @component('components.badge', ['type' => 'primary']) --}}
                {{-- pass props but laravel was {{slot}} --}}
                {{-- Brand new post! --}}
            {{-- @endcomponent --}}

            {{-- specify full component template name in AppServiceProvider.php and component folder --}}
            @badge(['show' => now()->diffInMinutes($post->created_at) < 30])
                Brand new post!
                {{-- $slot --}}
            @endbadge
        @if($post->image)
                </h1>
            </div>
        @else
            </h1>
        @endif
        <p>{{ $post->content }}</p>

        {{-- image->path get from BlogPost.php image() function then call specify path --}}
        {{-- <img src="http://127.0.0.1:8000/{{ $post->image->path }}" alt="">
        <img src="{{ asset($post->image->path) }}"> --}}
        {{-- to use Storage facade, it must configure in .env FILESYSTEM_DRIVER=s3 or public --}}
        {{-- <img src="{{ Storage::url($post->image->path) }}"> --}}

        {{-- get from Image.php which associated with BlogPost.php's image() --}}
        {{-- <img src="{{ $post->image->url() }}"> --}}

        {{-- diffForHumans() show how much time passed since  --}}

        {{-- specify full component template name in AppServiceProvider.php and component folder --}}
        @updated(['date' => $post->created_at, 'name' => $post->user->name])
        @endupdated

        @updated(['date' => $post->updated_at])
            Updated
        @endupdated

        {{--
            $post->tags was get from BlogPost.php line at 39 without calling tags() function, remember many to many has to create new table associate with origin table with multiple foreign id in it,
            for instance, "blog_posts" and "tags" table was related,
            but I rather create "blog_post_tag" table with "blog_post_id" and "tag_id" column foregin key among this table.
        --}}
        @tags(["tags" => $post->tags])@endtags

        <p>Currently read by {{ $counter }} people</p>

        {{-- @isset($post['has_comments'])
        <div>The post has some comment...using isset</div>

        @endisset --}}

        <h4>Comments</h4>

        {{-- @include("comments.form")  -- instead of include we add AppServiceProvider.php at components/comment-form.blade.php and pass the route array props --}}
        @commentForm(["route" => route("posts.comments.store", ["post" => $post->id])])
        @endcommentForm

        @commentList(["comments" => $post->comments])
        @endcommentList

        {{-- @forelse($post->comments as $comment)
            <p class="text-muted">
                {{ $comment->content }} --}}
                {{-- , added {{ $comment->created_at->diffForHumans() }} --}}
            {{-- </p> --}}
            {{-- specify full component template name in AppServiceProvider.php and component folder --}}
            {{-- @updated(['date' => $comment->created_at, 'name' => $comment->user->name])
            @endupdated
        @empty
            <p>No comments yet!</p>
        @endforelse --}}
    </div>

    {{-- find it from AppServiceProvider.php --}}
    <div class="col-4">
        @include("posts.partials.activity")
    </div>
</div>
@endsection
