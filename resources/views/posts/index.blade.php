@extends('layouts.app')

@section('title', 'Blog Posts')

@section('content')
{{-- each directive would render no empty post include function --}}
{{-- @each('posts.partials.post', $posts, 'post') --}}
    @forelse ($posts as $key => $post)
        @include('posts.partials.post', [])
    @empty
        No posts found!
    @endforelse
@endsection
