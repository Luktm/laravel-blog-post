@extends('layouts.app')

@section('title', 'Blog Posts')

@section('content')
    {{-- each directive would render no empty post include function --}}
    {{-- @each('posts.partials.post', $posts, 'post') --}}
    <div class="row">
        <div class="col-8">
            @forelse ($posts as $key => $post)
                @include('posts.partials.post', [])
            @empty
                No posts found!
            @endforelse
        </div>

        {{-- this class passed from AppServiceProvider.php at line 37 --}}
        <div class="col-4">
            @include("posts.partials.activity")
        </div>
    </div>
@endsection
