@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-4">
            <img src="{{ $user->image ? $user->image->url() : '' }}" alt="" class="img-thumbnail avatar">
        </div>
        <div class="col-8">
            <h3>{{ $user->name }}</h3>
            {{-- {{ dd($user->id) }} --}}
            {{-- we add AppServiceProvider.php at components/comment-form.blade.php and pass the route array props --}}
            {{-- remember route() always pass to web.php --}}
            @commentForm(["route" => route("users.comments.store", ["user" => $user->id])])
            @endcommentForm

            {{-- commentsOn come from User.php --}}
            @commentList(["comments" => $user->commentsOn])
            @endcommentList

        </div>
    </div>
@endsection
