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

<h1>{{ $post->title }}</h1>
<p>{{ $post->content }}</p>

{{-- diffForHumans() show how much time passed since  --}}
<p>Added {{ $post->created_at->diffForHumans() }}</p>
@if(now()->diffInMinutes($post->created_at) < 5)
    <div class="alert alert-info">New!</div>
@endif
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

@endsection
