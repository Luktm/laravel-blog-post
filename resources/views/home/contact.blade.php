@extends('layouts.app')

@section('title', 'contact')

@section('content')
    <h1>Contact page</h1>
    <p>Hello this is contact</p>

    {{-- please find this at AuthServiceProvider.php at line 36--}}
    @can('home.secret')
        <p>
            {{-- either put home.secret or secret equivalent web.php at line 34, therefore HomeController.php too --}}
            <a href="{{ route('secret') }}">
                Special contact details
            </a>
        </p>
    @endcan
@endsection
