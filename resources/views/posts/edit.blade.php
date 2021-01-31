@extends('layouts.app')

@section('title', 'Update the post')

@section('content')
{{-- pass $post->id come from PostsController--}}
<form action="{{ route('posts.update', ['post' => $post->id]) }}", method='POST'>
    @csrf
    @method('PUT')
    {{-- value={{old('title')}} retrive the old session input--}}
    @include('posts.partials.form')
    <div><input type="submit" value="Create"></div>
</form>
@endsection