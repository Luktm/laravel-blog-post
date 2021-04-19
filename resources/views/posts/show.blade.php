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
        <h1>
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

        </h1>
        <p>{{ $post->content }}</p>

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

        @forelse($post->comments as $comment)
            <p class="text-muted">
                {{ $comment->content }}, added {{ $comment->created_at->diffForHumans() }}
            </p>
        @empty
            <p>No comments yet!</p>
        @endforelse
    </div>

    {{-- find it from AppServiceProvider.php --}}
    <div class="col-4">
        @include("posts.partials.activity")
    </div>
</div>
@endsection
