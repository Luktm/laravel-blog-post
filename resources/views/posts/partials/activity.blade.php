<div class="container">
    <div class="row">
        {{-- 154 ep, complicated example of conditional rendering, please find it at AppServiceProvider.php --}}
        {{-- collection and auto loop it inside card.blade.php, only name display out --}}
        @card(['title' => 'Most Commented'])
        @slot('subtitle')
            What people are currently talking about
        @endslot
        @slot('items')
            @foreach ($mostCommented as $post)
                <li class="list-group-item">
                    <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                        {{ $post->title }}
                    </a>
                </li>
            @endforeach
        @endslot
        @endcard
        {{-- <div class="card" style="width: 100%;">
            <div class="card-body">
                <h5 class="card-title">Most Commented</h5>
                <h6 class="card-text text-muted mb-2">What people are currently talking about</h6>
            </div>
            <ul class="list-group list-group-flush">
                get from PostController.php index local scope query
                @foreach ($mostCommented as $post)
                    <li class="list-group-item">
                        <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                            {{ $post->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div> --}}
    </div>

    <div class="row mt-4">
        {{-- <div class="card" style="width: 100%;">
            <div class="card-body">
                <h5 class="card-title">Most Active</h5>
                <h6 class="card-text text-muted mb-2">User with most posts written</h6>
            </div>
            <ul class="list-group list-group-flush">
                // get from PostController.php index local scope query
                @foreach ($mostActive as $user)
                    <li class="list-group-item">
                        <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                            {{ $user->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div> --}}

        {{-- @card(['title' => 'Most Active', 'subtitle' => 'Users with most posts written']) --}}
        @card(['title' => 'Most Active'])
        {{-- collect was the list of collection with array --}}
        {{-- 154 ep, complicated example of conditional rendering, please find it at AppServiceProvider.php --}}
        {{-- pluck pick one property --}}
        {{-- @slot('subtitle') is equivalent to @card(['subtitle' => 'Users with most posts written']) --}}
        {{-- collection and auto loop it inside card.blade.php, only name display out --}}

        @slot('subtitle')
            Writers with most posts written
        @endslot

        @slot('items', collect($mostActive)->pluck('name'))
            @endcard
        </div>

        <div class="row mt-4">
            {{-- <div class="card" style="width: 100%;">
                <div class="card-body">
                    <h5 class="card-title">Most Active Last Month</h5>
                    <h6 class="card-text text-muted mb-2">User with most posts written in the last month</h6>
                </div>
                <ul class="list-group list-group-flush">
                    get from PostController.php index local scope query
                    @foreach ($mostActiveLastMonth as $user)
                        <li class="list-group-item">
                            <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                                {{ $user->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div> --}}

            @card(['title' => 'Most Active'])
            {{-- @slot('subtitle') is equivalent to @card(['subtitle' => 'Users with most posts written']) --}}
            {{-- 154 ep, complicated example of conditional rendering, please find it at AppServiceProvider.php --}}
            @slot('subtitle')
                Users with most posts written
            @endslot

            {{-- collection and auto loop it inside card.blade.php, only name display out --}}
            @slot('items', collect($mostActiveLastMonth)->pluck('name'))
                @endcard
        </div>
    </div>
</div>
