@extends('layouts.app')

@section('title', 'Create the post')

@section('content')
{{-- enctype multipart need to add for submit file --}}
<form action="{{ route('posts.store') }}", method='POST' enctype="multipart/form-data">
    @csrf
    {{-- value={{old('title')}} retrive the old session input--}}
    @include('posts.partials.form')
    <div><input type="submit" value="Create" class="btn btn-primary btn-block"></div>
</form>
@endsection
