@extends('layouts.app')

@section('title', 'home page')

@section('content')
    {{-- <h1>Hello world!</h1> --}}
    {{-- look at config/app.php, resources/lang/messages and get the "hello" key there --}}
    {{-- select prefer choice --}}
    <h1>{{ __("messages.hello") }}</h1>
    <h1>@lang("messages.hello")</h1>

    {{-- messages is file name --}}
    {{-- pass name props to all resources/lang messages files wich has :name --}}
    <p>{{ __("messages.example_with_value", ["name" => "John"]) }}</p>

    {{-- this pass to messages.php, plural key has {0} it return "No comments yet" --}}
    {{-- ["a" => 1] pass to :a in messages.php --}}
    <p>{{ trans_choice("messages.plural", 0, ["a" => 1]) }}</p>

    {{-- this pass to messages.php, plural key has |[1,2] it return "1 comments" --}}
    <p>{{ trans_choice("messages.plural", 1, ["a" => 1]) }}</p>
    {{-- this pass to messages.php, plural key has |[1,2] it return "2 comments" --}}
    <p>{{ trans_choice("messages.plural", 2, ["a" => 1] ) }}</p>

    {{-- create json like en.json es.json in lang and key must be the same then call it in here--}}
    <p>USING JSON: {{ __("Hello world!") }}</p>
    {{-- call the key and pass the name props to en.json es.json --}}
    <p>USING JSON: {{ __("Hello :name", ["name"=> "luk"]) }}</p>


    <div>
        @for ($i = 0; $i < 10; $i++)
            <div>The current value is {{ $i }} </div>
        @endfor
    </div>

    <div>
        @php $done = false @endphp
        @while (!$done)
            <div>I'm not done</div>

            @php
            if(random_int(0,1) == 1) $done = true
            @endphp
        @endwhile
    </div>
@endsection
