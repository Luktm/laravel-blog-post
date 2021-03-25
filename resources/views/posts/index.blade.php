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
        <div class="col-4">
            <div class="container">
                <div class="row">
                    <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">Most Commented</h5>
                            <h6 class="card-text text-muted mb-2">What people are currently talking about</h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            {{-- get from PostController.php index local scope query --}}
                            @foreach ($mostCommented as $post)
                                <li class="list-group-item">
                                    <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                                        {{ $post->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">Most Active</h5>
                            <h6 class="card-text text-muted mb-2">User with most posts written</h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            {{-- get from PostController.php index local scope query --}}
                            @foreach ($mostActive as $user)
                                <li class="list-group-item">
                                    <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                                        {{ $user->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">Most Active Last Month</h5>
                            <h6 class="card-text text-muted mb-2">User with most posts written in the last month</h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            {{-- get from PostController.php index local scope query --}}
                            @foreach ($mostActiveLastMonth as $user)
                                <li class="list-group-item">
                                    <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                                        {{ $user->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
