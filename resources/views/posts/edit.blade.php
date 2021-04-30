@extends('layouts.app')

@section('title', 'Update the post')

@section('content')
{{-- pass $post->id come from PostsController--}}
{{-- enctype multipart need to add for submit file --}}
<form action="{{ route('posts.update', ['post' => $post->id]) }}", method='POST' enctype="multipart/form-data">
    @csrf
    @method('PUT')
    {{-- value={{old('title')}} retrive the old session input--}}
    @include('posts.partials.form')
    <div><input class="btn btn-primary btn-block" type="submit" value="Update"></div>
</form>
@endsection
